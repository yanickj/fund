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
            foreach($participants as $participant)
            {
                $this->url = $this->generateUrl();
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

    public function generateUrl()
    {
        $params = [
            'url' => "https://slack.com/api/chat.postMessage?",
            'token' => $this->getContainer()->getParameter('client_token'),
            'channel' => '%40sharon',
            'text' => 'hi',
            'username' => 'SideFun(d)',
            'icon_emoji' => '%3Aparty%3A',
            'pretty' => 1,
        ];

        return $params['url'].
            '?token='.$params['token'].
            '&channel='.$params['channel'].
            '&text='.$params['text'].
            '&username='.$params['uesrname'].
            '&icon_emoji='.$params['icon_emoji'].
            '&pretty='.$params['pretty'];
    }

    public function sendCurlRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_exec($ch);
        curl_close($ch);
    }

}