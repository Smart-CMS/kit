<?php

namespace SmartCms\Kit\Observers;

use SmartCms\Forms\Models\ContactForm;
use SmartCms\Kit\Models\Admin;
use SmartCms\Kit\Notifications\NewContactFormNotification;

class ContactFormObserver
{
    public function saved(ContactForm $contactForm)
    {
        foreach (Admin::all() as $admin) {
            $admin->notify(new NewContactFormNotification($contactForm));
        }
    }
}
