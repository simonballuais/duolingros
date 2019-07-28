<?php
namespace AppBundle\Model;


class Correction implements CorrectionInterface
{
    protected $remarks;
    protected $isOkDespiteRemarks;
    protected $rightAnswer;

    public function __construct($remarks = [])
    {
        $this->remarks = $remarks;
        $this->isOkDespiteRemark = false;
        $this->rightAnswer = null;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function addRemark($remark)
    {
        $this->remarks[] = $remark;
    }

    public function isOk()
    {
        return $this->isOkDespiteRemark || count($this->remarks) === 0;
    }

    public function getIsOkDespiteRemarks()
    {
        return $this->isOkDespiteRemarks;
    }

    public function setIsOkDespiteRemark($isOkDespiteRemark)
    {
        $this->isOkDespiteRemark = $isOkDespiteRemark;

        return $this;
    }

    public function getRightAnswer()
    {
        return $this->rightAnswer;
    }

    public function setRightAnswer($rightAnswer)
    {
        $this->rightAnswer = $rightAnswer;

        return $this;
    }
}

