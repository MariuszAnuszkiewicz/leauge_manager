<?php namespace MariuszAnuszkiewicz\classes\ValidateInput;


class ValidateInput
{
    const EMPTY_INPUT = "Pole jest puste, proszę uzupełnić to pole";
    const STRING_LENGTH_MAX = "Dane wprowadzone są za długie, pole może mieć do 60 znaków";
    const INVALID_PATTERN = "Dane wprowadzone do pola mają nieprawidłowe znaki";

    public function validate($input)
    {
        $status = true;

        if (strlen($input) < 1) {
           echo self::EMPTY_INPUT;
           $status = false;
        }
        if (strlen($input) > 60) {
           echo self::STRING_LENGTH_MAX;
           $status = false;
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $input)) {
           echo self::INVALID_PATTERN;
           $status = false;
        }
        return $status;
    }
}