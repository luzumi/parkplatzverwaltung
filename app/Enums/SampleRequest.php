<?php

namespace App\Enums;

enum SampleRequest: string
{
    case Only6Letters = 'ABCdef';
    case Only3Numbers = '123';
    case NumbersAndLetters = '123ABC';
    case NumberLettersSpaces = 'ABC 123';
    case NoSigns = '';
    case ValidEmail = 'test@test.test';
    case ValidPassword = 'password';
    case SignWithSymbols = 'test; 123 {';
    case SqlInject = ' OR 1=1; --';
    case Image = 'image.jpg';
    case LongText300Sign = 'asdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöwe
    asdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöwe
    asdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöweasdfjklöwe';
}
