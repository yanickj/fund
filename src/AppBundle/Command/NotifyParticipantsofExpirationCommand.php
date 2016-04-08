<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Validator\Constraints\DateTime;

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
                $url = $this->generateUrl();
                $this->sendCurlRequest($url);
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
        return [1];
    }

    public function generateUrl()
    {
        $params = [
            'url' => "https://slack.com/api/chat.postMessage?",
            'token' => 1,
            'channel' => 1,
            'text' => 1,
            'username' => 1,
            'icon_emoji' => 1,
            'pretty' => 1,
        ];

    }

    public function sendCurlRequest()
    {

    }

}