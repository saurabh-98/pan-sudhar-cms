<?php

namespace App\Services;

use App\DTO\LeaveDTO;
use App\Repositories\LeaveRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function __construct(
        protected LeaveRepository $repo
    ) {}

    /* =========================================================
     | LIST
     *=========================================================*/
    public function getAll()
    {
        return $this->repo->all();
    }

    /* =========================================================
     | FIND
     *=========================================================*/
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /* =========================================================
     | STORE
     *=========================================================*/
    public function create(LeaveDTO $dto)
    {
        DB::beginTransaction();

        try {

            $data = $dto->toArray();

            /* ================= TOTAL DAYS ================= */
            $data['total_days'] = $this->calculateDays($dto);

            /* ================= DEFAULT STATUS ================= */
            $data['status'] = 'Pending';

            $leave = $this->repo->create($data);

            DB::commit();

            return $leave;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | UPDATE
     *=========================================================*/
    public function update($id, LeaveDTO $dto)
    {
        DB::beginTransaction();

        try {

            $leave = $this->find($id);

            /* ================= BLOCK AFTER APPROVAL ================= */
            if ($leave->status === 'Approved') {

                throw new \Exception(
                    'Approved leave cannot be modified.'
                );
            }

            $data = $dto->toArray();

            /* ================= RECALCULATE DAYS ================= */
            $data['total_days'] = $this->calculateDays($dto);

            $leave = $this->repo->update($id, $data);

            DB::commit();

            return $leave;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | DELETE
     *=========================================================*/
    public function delete($id)
    {
        $leave = $this->find($id);

        /* ================= BLOCK DELETE ================= */
        if ($leave->status === 'Approved') {

            throw new \Exception(
                'Approved leave cannot be deleted.'
            );
        }

        return $this->repo->delete($id);
    }

    /* =========================================================
     | APPROVE
     *=========================================================*/
    public function approve($id)
    {
        DB::beginTransaction();

        try {

            $leave = $this->find($id);

            /* ================= ALREADY APPROVED ================= */
            if ($leave->status === 'Approved') {

                throw new \Exception(
                    'Leave already approved.'
                );
            }

            $leave = $this->repo->updateStatus(
                $id,
                'Approved'
            );

            DB::commit();

            return $leave;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | REJECT
     *=========================================================*/
    public function reject($id, $remark = null)
    {
        DB::beginTransaction();

        try {

            $leave = $this->find($id);

            /* ================= ALREADY REJECTED ================= */
            if ($leave->status === 'Rejected') {

                throw new \Exception(
                    'Leave already rejected.'
                );
            }

            $leave = $this->repo->updateStatus(
                $id,
                'Rejected',
                $remark
            );

            DB::commit();

            return $leave;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | PENDING LEAVES
     *=========================================================*/
    public function getPendingLeaves()
    {
        return $this->repo->getByStatus('Pending');
    }

    /* =========================================================
     | APPROVED LEAVES
     *=========================================================*/
    public function getApprovedLeaves()
    {
        return $this->repo->getByStatus('Approved');
    }

    /* =========================================================
     | REJECTED LEAVES
     *=========================================================*/
    public function getRejectedLeaves()
    {
        return $this->repo->getByStatus('Rejected');
    }

    /* =========================================================
     | REPORT
     *=========================================================*/
    public function getLeaveReport()
    {
        return [

            'totalLeaves' => $this->repo->count(),

            'approvedLeaves' => $this->repo
                ->countByStatus('Approved'),

            'rejectedLeaves' => $this->repo
                ->countByStatus('Rejected'),

            'pendingLeaves' => $this->repo
                ->countByStatus('Pending'),
        ];
    }

    /* =========================================================
     | CALCULATE DAYS
     *=========================================================*/
    private function calculateDays(LeaveDTO $dto)
    {
        $days = Carbon::parse($dto->from_date)
            ->diffInDays(
                Carbon::parse($dto->to_date)
            ) + 1;

        if ($dto->leave_duration === 'Half Day') {
            return 0.5;
        }

        return $days;
    }
}