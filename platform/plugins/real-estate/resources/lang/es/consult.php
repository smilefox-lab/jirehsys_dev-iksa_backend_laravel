<?php

use Botble\RealEstate\Enums\ConsultStatusEnum;

return [
    'name'                          => 'Consults',
    'edit'                          => 'View consult',
    'statuses'                      => [
        ConsultStatusEnum::READ   => 'Read',
        ConsultStatusEnum::UNREAD => 'UnRead',
    ],
    'phone'                         => 'Phone',
    'settings'                      => [
        'email' => [
            'title'       => 'Consult',
            'description' => 'Consult email configuration',
            'templates'   => [
                'notice_title'       => 'Send notice to administrator',
                'notice_description' => 'Email template to send notice to administrator when system get new consult',
            ],
        ],
    ],
    'content'                       => 'Details',
    'consult_information'           => 'Consult information',
    'email'                         => [
        'header'  => 'Email',
        'title'   => 'New consult from your site',
        'success' => 'Send consult successfully!',
        'failed'  => 'Can\'t send request on this time, please try again later!',
    ],
    'name.required'                 => 'Name is required',
    'email.required'                => 'Email is required',
    'email.email'                   => 'The email address is not valid',
    'content.required'              => 'Content is required',
    'g-recaptcha-response.required' => 'Please confirm you are not a robot before sending the message.',
    'g-recaptcha-response.captcha'  => 'You are not confirm robot yet.',
    'consult_sent_from'             => 'This consult information sent from',
    'time'                          => 'Time',
    'consult_id'                    => 'Consult ID',
    'form_name'                     => 'Name',
    'form_email'                    => 'Email',
    'form_phone'                    => 'Phone',
    'mark_as_read'                  => 'Mark as read',
    'mark_as_unread'                => 'Mark as unread',
    'new_consult_notice'            => 'You have <span class="bold">:count</span> New Consults',
    'view_all'                      => 'View all',
    'project'                       => 'Project',
    'property'                      => 'Property',
];
