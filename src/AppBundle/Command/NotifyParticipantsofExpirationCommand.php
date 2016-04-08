<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManager;

/**
 * Class NotifyParticipantsofExpirationCommand
 *
 * @package src\AppBundle\Command
 */
class NotifyParticipantsofExpirationCommand extends ContainerAwareCommand
{
    /**
     * Configure
     */
    public function configure()
    {
        $this
            ->setName('sidefund:expired_projects')
            ->setDescription('Notify participants regarding the funding status of their expired projects');
    }

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $expiredProjects = $this->getExpiredProjects();
        foreach($expiredProjects as $expiredProject)
        {
            $participants = $this->getParticipants($expiredProject);
            $expiredProjectCount = count($participants);
            foreach($participants as $participant)
            {
                $this->url = $this->generateUrl($participant,
                                                $expiredProject->getMinParticipants(),
                                                $expiredProjectCount,
                                                $expiredProject->getCost()/$expiredProjectCount,
                                                $expiredProject->getName());
                $this->sendCurlRequest();
            }
        }
    }

    public function getExpiredProjects()
    {
        return $this->em->getRepository('AppBundle:Project')
                        ->findBy(['expirationDate' => new DateTime()]);
    }

    public function getParticipants($expiredProject)
    {
        return $this->em->getRepository('AppBundle:Participant')
            ->findBy(['project' => $expiredProject]);
    }

    public function generateUrl($participant,
                                $expiredProjectMin,
                                $expiredProjectCount,
                                $expiredProjectCost,
                                $expiredProjectName)
    {
        $params = [
            'url' => "https://slack.com/api/chat.postMessage",
            'token' => $this->getContainer()->getParameter('client_token'),
            'channel' => '%40'.$participant->getName(),
            'text' => $this->getText($expiredProjectMin, $expiredProjectCount, $expiredProjectCost, $expiredProjectName),
            'username' => 'SideFun(d)',
            'icon_emoji' => '%3Aparty%3A',
            'pretty' => 1,
        ];

        return $params['url'].
            '?token='.$params['token'].
            '&channel='.$params['channel'].
            '&text='.$params['text'].
            '&username='.$params['username'].
            '&icon_emoji='.$params['icon_emoji'].
            '&pretty='.$params['pretty'];
    }

    public function sendCurlRequest()
    {
        echo $this->url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_exec($ch);
        curl_close($ch);
    }

    public function getText($expiredProjectMin, $expiredProjectCount, $expiredProjectCost, $expiredProjectName)
    {
        if ($expiredProjectMin >= $expiredProjectCount)
        {
            return urlencode('Hooray! Your project, '.$expiredProjectName.' was funded! You owe $'.$expiredProjectCost.'.');
        }
        
        return urlencode('Sorry. Not enough people funded '.$expiredProjectName.'.');
    }

}