<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        $pages = [

            [
                'title' => 'About Us',

                'slug'  => 'about-us',

                'content' => '

                    <section>

                        <h2>PAN & Aadhaar Suvidha Kendra</h2>

                        <p>
                            Welcome to our PAN & Aadhaar Suvidha Portal.
                            We provide fast, secure, and reliable PAN Card,
                            Aadhaar, and document-related online services.
                        </p>

                        <p>
                            Our goal is to simplify government documentation
                            processes for citizens with transparency,
                            accuracy, and digital support.
                        </p>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'Contact Us',

                'slug'  => 'contact-us',

                'content' => '

                    <section>

                        <h2>Contact Information</h2>

                        <p>
                            📍 Address:
                            Mithapur, Patna, Bihar, India
                        </p>

                        <p>
                            📞 Mobile:
                            +91 9876543210
                        </p>

                        <p>
                            ✉ Email:
                            support@panaadhaarsuvidha.com
                        </p>

                        <p>
                            🕒 Working Hours:
                            Monday to Saturday (9:00 AM - 7:00 PM)
                        </p>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'Privacy Policy',

                'slug'  => 'privacy-policy',

                'content' => '

                    <section>

                        <h2>Privacy Policy</h2>

                        <p>
                            We respect and protect customer privacy.
                            Personal details submitted through this portal
                            are kept secure and confidential.
                        </p>

                        <p>
                            We do not share Aadhaar, PAN, or personal
                            information with unauthorized third parties.
                        </p>

                        <p>
                            All uploaded documents are processed securely
                            according to applicable government guidelines.
                        </p>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'Terms & Conditions',

                'slug'  => 'terms-conditions',

                'content' => '

                    <section>

                        <h2>Terms & Conditions</h2>

                        <p>
                            By using this portal, users agree to provide
                            valid and authentic information for PAN and
                            Aadhaar related services.
                        </p>

                        <p>
                            Any misuse, fake documentation, or fraudulent
                            activity is strictly prohibited.
                        </p>

                        <p>
                            Service charges once paid are subject to
                            applicable refund policies.
                        </p>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'PAN Card Services',

                'slug'  => 'pan-card-services',

                'content' => '

                    <section>

                        <h2>PAN Card Services</h2>

                        <p>
                            We provide complete PAN Card services including:
                        </p>

                        <ul>

                            <li>New PAN Card Apply</li>

                            <li>PAN Correction</li>

                            <li>Duplicate PAN Card</li>

                            <li>e-PAN Download</li>

                            <li>PAN-Aadhaar Linking</li>

                        </ul>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'Aadhaar Services',

                'slug'  => 'aadhaar-services',

                'content' => '

                    <section>

                        <h2>Aadhaar Services</h2>

                        <p>
                            We provide Aadhaar-related online assistance
                            and update services.
                        </p>

                        <ul>

                            <li>Mobile Number Update</li>

                            <li>Address Update</li>

                            <li>Date of Birth Correction</li>

                            <li>Name Correction</li>

                            <li>Aadhaar PVC Card Assistance</li>

                        </ul>

                    </section>

                ',

                'status' => 1
            ],

            [
                'title' => 'Document Services',

                'slug'  => 'document-services',

                'content' => '

                    <section>

                        <h2>Online Document Services</h2>

                        <p>
                            We also assist citizens with digital
                            documentation and online applications.
                        </p>

                        <ul>

                            <li>Voter ID Services</li>

                            <li>Income Certificate</li>

                            <li>Caste Certificate</li>

                            <li>Residence Certificate</li>

                            <li>Birth Certificate Assistance</li>

                        </ul>

                    </section>

                ',

                'status' => 1
            ],

        ];

        foreach ($pages as $page) {

            Page::updateOrCreate(

                [
                    'slug' => $page['slug']
                ],

                [
                    'title'   => $page['title'],

                    'content' => $page['content'],

                    'status'  => $page['status']
                ]

            );
        }
    }
}