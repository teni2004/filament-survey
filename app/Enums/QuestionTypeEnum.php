<?php

namespace App\Enums;

enum QuestionTypeEnum : string {
    case RATING = 'rating';
    case YESNO = 'yes-no';
    case MULTIPLECHOICE = 'multiple-choice';
    case SELECTONE = 'select-one';
    case FREEFORM = 'free-form';
}