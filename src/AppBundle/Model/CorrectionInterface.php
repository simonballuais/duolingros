<?php
namespace AppBundle\Model;

interface CorrectionInterface
{
    public function addRemark($remark);
    public function getRemarks();
    public function getRightAnswer();
}

