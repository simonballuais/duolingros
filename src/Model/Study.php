<?php
namespace App\Model;


class Study
{
    protected $lessonId;

    public function __construct($lessonId)
    {
        $this->lessonId = $lessonId;
    }

    public function getLessonId()
    {
        return $this->lessonId;
    }

    public function setLessonId($lessonId)
    {
        $this->lessonId = $lessonId;

        return $this;
    }
}
?>
