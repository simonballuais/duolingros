<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Editor;


class GenerateRandomIndicatorValuesCommand extends ContainerAwareCommand
{
    const DEFAULT_AMPLITUDE = 2;
    const DEFAULT_METABOLISM = 0.25;
    const DEFAULT_TIME_INTERVAL_ERROR = 0;
    const DEFAULT_ROUND_DECIMALS = 2;
    const DATE_FORMAT = 'd/m/Y H:i';

    protected function configure()
    {
            $this
                ->setName('app:generate-data')
                ->setDescription('Generate random indicator values')
                ->setHelp('Generate random indicator values')
                ->setDefinition(
                    new InputDefinition([
                            new InputOption('startValue', 'S', InputOption::VALUE_REQUIRED),
                            new InputOption('endValue', 'E', InputOption::VALUE_REQUIRED),
                            new InputOption('userId', 'u', InputOption::VALUE_REQUIRED),
                            new InputOption('indicatorTypeId', 'i', InputOption::VALUE_REQUIRED),
                            new InputOption('editorId', null, InputOption::VALUE_REQUIRED),
                            new InputOption('startDate', null, InputOption::VALUE_REQUIRED),
                            new InputOption('timeInterval', null, InputOption::VALUE_REQUIRED),
                            new InputOption('endDate', null, InputOption::VALUE_REQUIRED),
                            new InputOption('amplitude', 'a', InputOption::VALUE_OPTIONAL),
                            new InputOption('metabolism', 'm', InputOption::VALUE_OPTIONAL),
                            new InputOption('timeIntervalError', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('isTension', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('startOffPeriod', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('endOffPeriod', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('errorOffPeriod', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('variationOffPeriod', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('roundDecimals', null, InputOption::VALUE_OPTIONAL),
                            new InputOption('noNegative', null, InputOption::VALUE_OPTIONAL),
                        ])
                    )
                ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startValue = floatval($input->getOption('startValue'));
        $endValue = floatval($input->getOption('endValue'));
        $startDate = \DateTime::createFromFormat(self::DATE_FORMAT, $input->getOption('startDate'));
        $endDate = \DateTime::createFromFormat(self::DATE_FORMAT, $input->getOption('endDate'));
        $timeInterval = floatval($input->getOption('timeInterval'));
        $amplitude = $input->getOption('amplitude');
        $metabolism = $input->getOption('metabolism');
        $timeIntervalError = $input->getOption('timeIntervalError');
        $userId = $input->getOption('userId');
        $indicatorTypeId = $input->getOption('indicatorTypeId');
        $editorId = $input->getOption('editorId');
        $isTension = !!$input->getOption('isTension');
        $startOffPeriod = $input->getOption('startOffPeriod');
        $endOffPeriod = $input->getOption('endOffPeriod');
        $errorOffPeriod = intval($input->getOption('errorOffPeriod'));
        $variationOffPeriod = floatval($input->getOption('variationOffPeriod'));
        $roundDecimals = $input->getOption('roundDecimals');
        $noNegative = !!$input->getOption('noNegative');

        $um = $this->getContainer()->get('fos_user.user_manager');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repoIndicatorType = $em->getRepository("AppBundle:IndicatorValue\IndicatorType");

        $indicatorType = $repoIndicatorType->findOneById($indicatorTypeId);

        if (null === $indicatorType) {
            $output->writeln('indicatorType inexistant');

            return;
        }

        if (null === $amplitude) {
            $amplitude = self::DEFAULT_AMPLITUDE;
        }

        $amplitude = floatval($amplitude);

        if (null === $metabolism) {
            $metabolism = self::DEFAULT_AMPLITUDE;
        }

        $metabolism = floatval($metabolism);

        if (null === $timeIntervalError) {
            $timeIntervalError = self::DEFAULT_TIME_INTERVAL_ERROR;
        }

        $timeIntervalError = floatval($timeIntervalError);

        if (null === $roundDecimals) {
            $roundDecimals = self::DEFAULT_ROUND_DECIMALS;
        }

        $roundDecimals = intval($roundDecimals);

        $output->writeln('{');
        $output->writeln('    "idUser": ' . $userId . ',');
        $output->writeln('    "idEditor": "' . $editorId . '",');
        $output->writeln('    "idIndicatorType": "' . $indicatorTypeId . '",');
        $output->writeln('    "values":');
        $output->writeln('    [');

        $endTime = $endDate->getTimestamp();
        $currentTime = $startDate->getTimestamp();
        $startTime = $startDate->getTimestamp();

        $totalValueProgression = $endValue - $startValue;
        $totalTime = $endTime - $startTime;

        $currentVariation = 0;

        $positiveOrNegative = [-1, 1];

        if (!$isTension) {
            while ($currentTime < $endTime) {
                $valueDate = new \DateTime();
                $valueDate->setTimestamp($currentTime);

                $elapsedTime = $currentTime - $startTime;
                $currentNoiselessValue = $startValue + (($elapsedTime / $totalTime) * $totalValueProgression);

                $currentVariation += $metabolism * $positiveOrNegative[rand(0, 1)] * rand(1, $amplitude);

                if ($currentVariation > $amplitude) {
                    $currentVariation = $amplitude;
                }

                if ($currentVariation < - $amplitude) {
                    $currentVariation = - $amplitude;
                }

                $noisedValue = round($currentNoiselessValue + $currentVariation, $roundDecimals);

                if ($startOffPeriod !== null) {
                    $currentDay = $valueDate->format('d/m/Y');

                    $startOffPeriodTime = \DateTime::createFromFormat(self::DATE_FORMAT, $currentDay . ' ' . $startOffPeriod);
                    $timeError = rand(0, $errorOffPeriod);
                    $startOffPeriodTime->modify("+{$timeError} seconds");

                    $endOffPeriodTime = \DateTime::createFromFormat(self::DATE_FORMAT, $currentDay . ' ' . $endOffPeriod);
                    $timeError = rand(0, $errorOffPeriod);
                    $endOffPeriodTime->modify("+{$timeError} seconds");

                    if ($valueDate->getTimestamp() < $endOffPeriodTime->getTimestamp()
                        || $valueDate->getTimestamp() > $startOffPeriodTime->getTimestamp()) {

                        $noisedValue += $variationOffPeriod;
                    }
                }

                if ($noNegative && $noisedValue < 0) {
                    $noisedValue = 0;
                }

                $output->writeln('        { '
                    . '"value": "' . $noisedValue . '"'
                    . ', "unit": ""'
                    . ', "date": "' . $valueDate->format(self::DATE_FORMAT) . '" },');

                $timeIncrease = $timeInterval + (rand(0, $timeIntervalError) * $positiveOrNegative[rand(0, 1)]);
                $currentTime += $timeIncrease;
            }
        }
        else {
            $startValue = explode('.', $startValue);
            $endValue = explode('.', $endValue);
            $totalValueProgression = [
                $endValue[0] - $startValue[0],
                $endValue[1] - $startValue[1],
            ];
            $amplitude = explode('.', $amplitude);
            $currentVariation = [0, 0];

            while ($currentTime < $endTime) {
                $valueDate = new \DateTime();
                $valueDate->setTimestamp($currentTime);

                $elapsedTime = $currentTime - $startTime;

                for ($i = 0; $i < 2; $i++)
                {
                    $currentNoiselessValue[$i] = $startValue[$i] + (($elapsedTime / $totalTime) * $totalValueProgression[$i]);

                    $currentVariation[$i] += $metabolism * $positiveOrNegative[rand(0, 1)] * $amplitude[$i];

                    if ($currentVariation[$i] > $amplitude[$i]) {
                        $currentVariation[$i] = $amplitude[$i];
                    }

                    if ($currentVariation[$i] < - $amplitude[$i]) {
                        $currentVariation[$i] = - $amplitude[$i];
                    }

                    $noisedValue[$i] = round($currentNoiselessValue[$i] + $currentVariation[$i]);
                }

                $assembledValue = ($noisedValue[0] . '.' . $noisedValue[1]);

                $output->writeln('        { '
                    . '"value": "' . $assembledValue . '"'
                    . ', "unit": ""'
                    . ', "date": "' . $valueDate->format(self::DATE_FORMAT) . '" },');

                $timeIncrease = $timeInterval + (rand(0, $timeIntervalError) * $positiveOrNegative[rand(0, 1)]);
                $currentTime += $timeIncrease;
            }
        }

        $output->writeln('    ]');
        $output->writeln('}');
    }
}
