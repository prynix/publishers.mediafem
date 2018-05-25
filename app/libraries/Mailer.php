<?php

class Mailer {
    /*
     * Envía un correo electronico
     *
     * @param string Vista a enviar
     * @param array Datos utilizados en la vista a enviar
     * @param string Email del remitente
     * @param string Nombre del remitente
     * @param string Asunto del mensaje
     */

    public static function send($view, $data, $to, $to_name, $subject) {
        try {
            return Mail::send($view, $data, function($message) use ($to, $to_name, $subject) {
                        $message->to($to, $to_name)->subject($subject);
                    });
        } catch (Exception $ex) {
            return ['result' => FALSE, 'message' => $ex->getMessage()];
        }
    }

}