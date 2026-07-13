<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels.
|
*/

/*
|--------------------------------------------------------------------------
| Conversation Channel
|--------------------------------------------------------------------------
|
| Admin and Retailer who belong to the conversation
| can join this private channel.
|
*/

Broadcast::channel(
    'conversation.{conversationId}',
    function ($user, string $conversationId) {

        $conversation = Conversation::where(
            'conversation_id',
            $conversationId
        )->first();

        if (!$conversation) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($user, 'hasRole') &&
            $user->hasRole('Admin')
        ) {

            return true;

        }

        /*
        |--------------------------------------------------------------------------
        | Retailer
        |--------------------------------------------------------------------------
        */

        return $conversation->retailer_id == $user->id;

    }
);

/*
|--------------------------------------------------------------------------
| Admin Chat Dashboard
|--------------------------------------------------------------------------
*/

Broadcast::channel(
    'admin.chat',
    function ($user) {

        return
            method_exists($user, 'hasRole')
            && $user->hasRole('Admin');

    }
);