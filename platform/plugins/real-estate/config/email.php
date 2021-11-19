<?php

return [
    'name'        => 'plugins/real-estate::consult.settings.email.title',
    'description' => 'plugins/real-estate::consult.settings.email.description',
    'templates'   => [
        'notice' => [
            'title'       => 'plugins/real-estate::consult.settings.email.templates.notice_title',
            'description' => 'plugins/real-estate::consult.settings.email.templates.notice_description',
            'subject'     => 'New consult',
            'can_off'     => true,
        ],
    ],
    'variables' => [
        'consult_name'    => 'Name',
        'consult_phone'   => 'Phone',
        'consult_email'   => 'Email',
        'consult_content' => 'Content',
        'consult_link'    => 'Link',
        'consult_subject' => 'Subject',
    ],
];
