<?php
/**
 * Created by PhpStorm.
 * User: alexandramills
 * Date: 4/7/16
 * Time: 10:20 PM
 */

namespace AppBundle\Twig;

use AppBundle\Service\ParticipationService;

/**
 * Class AppExtension
 *
 * @package AppBundle\Twig
 * @author Józef Janik <joe@getsidecar.com>
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var ParticipationService
     */
    protected $participation;

    /**
     * AppExtension constructor.
     *
     * @param ParticipationService $participation
     */
    public function __construct(ParticipationService $participation)
    {
        $this->participation = $participation;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isParticipant', [$this->participation, 'isParticipant']),
            new \Twig_SimpleFunction('costPerParticipant', [$this->participation, 'costPerParticipant']),
            new \Twig_SimpleFunction('maxCostPerParticipant', [$this->participation, 'maxCostPerParticipant']),
            new \Twig_SimpleFunction('getParticipantCount', [$this->participation, 'getParticipantCount']),
            new \Twig_SimpleFunction('countDown', [$this->participation, 'getDaysToRegister']),
        ];
    }

    public function getName()
    {
        return 'app_extension';
    }
}