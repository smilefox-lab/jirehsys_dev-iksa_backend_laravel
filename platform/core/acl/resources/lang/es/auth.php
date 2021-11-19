<?php

return [
    'login'                 => [
        'username'          => 'Usuario',
        'email'             => 'Email',
        'password'          => 'Contraseñas',
        'title'             => 'Inicio de sesión de usuario',
        'remember'          => 'Recuérdame',
        'login'             => 'Ingresar',
        'placeholder'       => [
            'username' => 'Por favor ingrese su usuario',
            'email'    => 'Por favor ingrese su email',
        ],
        'success'           => '¡Inicio de sesión exitoso!',
        'fail'              => 'Nombre de usuario o contraseña incorrectos.',
        'not_active'        => '¡Tu cuenta no ha sido activada todavía!',
        'banned'            => 'Esta cuenta está prohibida.',
        'logout_success'    => '¡Cierre de sesión exitoso!',
        'dont_have_account' => '¡No tiene una cuenta en este sistema, póngase en contacto con el administrador para obtener más información!',
    ],
    'forgot_password'       => [
        'title'   => 'Recuperar Contraseña',
        'message' => '<p>¿Ha olvidado tu su contraseña?</p><p>Por favor ingrese su e-mail. El siste enviara un e-mail con un enlace para recuperar su contraseña.</p>',
        'submit'  => 'Enviar',
    ],
    'reset'                 => [
        'new_password'          => 'Nueva contraseña',
        'password_confirmation' => 'Confirmar nueva contraseña',
        'email'                 => 'Email',
        'title'                 => 'Reestablcer sus contraseña',
        'update'                => 'Actualizar',
        'wrong_token'           => 'Este enlace no es valido o expiro. Por favor intente usar el formulario de recuperación de nuevo.',
        'user_not_found'        => 'Este usuario no existe.',
        'success'               => '¡Reinicio de contraseña exitoso!',
        'fail'                  => 'Token no es valido, en enlace de recuperación de contraseña ha expridado!',
        'reset'                 => [
            'title' => 'E-mail para reestablecer la contraseña',
        ],
        'send'                  => [
            'success' => 'El e-mail fue enviado a su cuenta de correo. Por favor revise y complete esta acción.',
            'fail'    => 'No se puede enviar el e-mail en este momento. Por favor intente de nuevo más tarde.',
        ],
        'new-password'          => 'Nueva contraseña',
    ],
    'email'                 => [
        'reminder' => [
            'title' => 'E-mail para reestablecer contraseña',
        ],
    ],
    'password_confirmation' => 'Confirmar contraseña',
    'failed'                => 'Falló',
    'throttle'              => 'Throttle',
    'not_member'            => 'Not a member yet?',
    'register_now'          => 'Registrar ahora',
    'lost_your_password'    => 'Recuperar su contraseña',
    'login_title'           => 'Admin',
    'login_via_social'      => 'Login with social networks',
    'back_to_login'         => 'Volver a la pagina de Inicio de sesión',
    'sign_in_below'         => 'Iniciar sesión a continuación',
    'languages'             => 'Idioma',
    'reset_password'        => 'Reestablecer contraseña',
];
