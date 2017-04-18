<?php

namespace SPHERE\Application\Platform\Utility\Translation;

use MOC\V\Component\Document\Component\Bridge\Repository\SymfonyYaml;
use MOC\V\Component\Document\Component\Parameter\Repository\FileParameter;
use MOC\V\Component\Document\Document;
use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Utility\Translation\Component\Localize;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\Conversation;
use SPHERE\Common\Frontend\Icon\Repository\Question;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Text\Repository\Code;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Frontend\Text\Repository\Tooltip;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Database\Link\Identifier;
use SPHERE\System\Extension\Repository\Debugger;

/**
 * Class Translation
 * @package SPHERE\Application\Platform\Utility\Translation
 */
class Translation implements IModuleInterface
{
    use AppTrait, TranslationTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __CLASS__, 'frontendDashboard', 'Mehrsprachigkeit', new Conversation());
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Platform', 'Utility', 'Translation'),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity');
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {

        $File = tempnam(sys_get_temp_dir(), 'tryamltest') . '.yaml';
//        Debugger::screenDump( $File );
        /** @var SymfonyYaml $Setting */
        $Setting = Document::getDocument($File);

        $T = $this->doTranslate(array(
            __METHOD__,
            'Identifier.1',
            'Identifier1',
            'Identifier.A'
        ),
            '{% if(Anzahl < 1) %}
                Keine Äpfel :( ({{ Kosten }})
             {% else %}
                {% if(Anzahl > 1) %}
                    {% if(Anzahl > 10) %}
                        Viele Äpfel XD
                    {% else %}
                        {{ Anzahl }} Äpfel kosten {{ Kosten }}
                    {% endif %}
                {% else %}
                   {{ Anzahl }} Apfel ist kostenlos
                {% endif %}
             {% endif %}'
            , array(
                'Anzahl' => 3 * 1,
                'Kosten' => $this->doLocalize(3 * 0.5)->getCurrency()
            ), TranslationInterface::LOCALE_DE_DE
        );

        $T->getPreset()->appendPattern('!.*?!is', ':P');

        $F = new TextField('',
            'Number',
            'Number'
        );

        $FT = new TextField('',
            $this->doTranslate(array('Textfeld', 'Platzhalter'), 'Number'),
            $this->doTranslate(array('Textfeld', 'Label'), 'Number')
        );

        $Path = $T->getDefinition();

        $Setting->setContent($Path);
        $Setting->saveFile(new FileParameter($File), 10);
//        Debugger::screenDump(
//            $Path,
//            file_get_contents($File)
//        );

        $PatternList = array_column($Setting->getContent(), 'Pattern');
//        $PatternList = array_map(function ($Region) {return $Region['Pattern'];}, $Setting->getContent());

        return (new Stage($T))->setContent(
            $F . $FT .
            (new Localize(time()))->getDateTime()
            . new Muted(new Tooltip($this->doTranslate(array('Question'), 'Question'), 'Answer', new Question()))
            . '<br/>'

            . new Code(file_get_contents($File), Code::TYPE_YAML)
            . new Code(print_r($PatternList, true))
        );
    }
}
