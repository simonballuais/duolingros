<?php
namespace AppBundle\Model;


class Correction implements CorrectionInterface
{
    protected $remarks;

    public function __construct($remarks = [])
    {
        $this->remarks = $remarks;
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
        return count($this->remarks) === 0;
    }
}
?>
