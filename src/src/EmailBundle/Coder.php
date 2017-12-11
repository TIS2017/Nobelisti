<?php

namespace EmailBundle;

class EmailMessageCoder
{
    public static function encode($emailId)
    {
        return json_encode([
            'MESSAGE_TYPE' => 'SEND_EMAIL',
            'MESSAGE_CONTENT' => [
                'EMAIL_ID' => $emailId,
            ],
        ]);
    }

    public static function decode($message)
    {
        $data = json_decode($message, true);

        return $data['MESSAGE_CONTENT']['EMAIL_ID'];
    }
}
