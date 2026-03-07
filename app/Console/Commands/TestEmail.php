<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;
use App\Models\Contact;

class TestEmail extends Command
{
    protected $signature = 'test:email {contact_id}';
    protected $description = 'Test sending email to customer';

    public function handle()
    {
        $contactId = $this->argument('contact_id');
        $contact = Contact::find($contactId);

        if (!$contact) {
            $this->error("Contact not found!");
            return 1;
        }

        $this->info("Testing email configuration...");
        $this->info("MAIL_MAILER: " . config('mail.default'));
        $this->info("MAIL_HOST: " . config('mail.mailers.smtp.host'));
        $this->info("MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
        $this->info("MAIL_FROM: " . config('mail.from.address'));
        $this->info("Sending to: " . $contact->email);

        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact));
            $this->info("✓ Email sent successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email:");
            $this->error($e->getMessage());
            return 1;
        }
    }
}
