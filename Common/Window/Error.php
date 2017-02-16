<?php
namespace SPHERE\Common\Window;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Common\Frontend\ITemplateInterface;
use SPHERE\Common\Frontend\Layout\Repository\Paragraph;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\System\Extension\Extension;

/**
 * Class Error
 *
 * @package SPHERE\Common\Window
 */
class Error extends Extension implements ITemplateInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var string $Code */
    private $Code = '';
    /** @var string $Message */
    private $Message = null;
    /** @var boolean $IsReportable */
    private $IsReportable = '';

    /**
     * @param integer|string $Code
     * @param null $Message
     * @param bool $IsReportable
     */
    public function __construct($Code, $Message = null, $IsReportable = true)
    {

        $this->Code = $Code;
        $this->IsReportable = $IsReportable;

        if (null === $Message) {
            switch ($Code) {
                case 404:
                    $Message = 'Die angeforderte Ressource konnte nicht gefunden werden';
                    break;
                default:
                    $Message = '';
            }
        } else {

            $Path = parse_url($this->getRequest()->getUrl(), PHP_URL_PATH);
            parse_str(parse_url($this->getRequest()->getUrl(), PHP_URL_QUERY), $Query);
            unset($Query['_Sign']);
            $Query = json_encode($Query);

            $Message = new Paragraph(
                    'Error-Log: [' . $this->getRequest()->getHost() . '] '
                    . $Path
                    . (strlen($Query) > 2
                        ? ' > ' . $Query
                        : ''
                    )
                ) . $Message;
        }

        $this->Message = $Message;

    }

    /**
     * @return string
     */
    public function __toString()
    {

        return (string)$this->getContent();
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $Stage = new Stage(
            new Danger('Es ist ein Fehler aufgetreten'), ($this->Code == '' ? null : 'Fehler-Code: ' . $this->Code)
        );

        $Stage->setContent(
            $this->Message
        );

        return $Stage;
    }
}
