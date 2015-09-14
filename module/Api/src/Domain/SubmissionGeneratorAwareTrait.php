<?php

namespace Dvsa\Olcs\Api\Domain;

use Dvsa\Olcs\Api\Service\Submission\SubmissionGenerator;

/**
 * SubmissionGeneratorAwareTrait
 */
trait SubmissionGeneratorAwareTrait
{
    /**
     * @var SubmissionGenerator
     */
    protected $submissionGenerator;

    /**
     * @var array $submissionConfig
     */
    protected $submissionConfig;

    /**
     * @param SubmissionGenerator $service
     */
    public function setSubmissionGenerator(SubmissionGenerator $service)
    {
        $this->submissionGenerator = $service;
    }

    /**
     * @return SubmissionGenerator
     */
    public function getSubmissionGenerator()
    {
        return $this->submissionGenerator;
    }

    /**
     * @return array
     */
    public function getSubmissionConfig()
    {
        return $this->submissionConfig;
    }

    /**
     * @param array $submissionConfig
     */
    public function setSubmissionConfig(array $submissionConfig)
    {
        $this->submissionConfig = $submissionConfig;
    }
}
