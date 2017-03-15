<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use MOC\V\Component\Document\Component\Bridge\Repository\SymfonyYaml;
use MOC\V\Component\Document\Component\Parameter\Repository\FileParameter;
use MOC\V\Component\Document\Document;
use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Translation\Component\Localize;
use SPHERE\Application\Platform\Utility\Translation\Component\Localize\Number;
use SPHERE\Application\Platform\Utility\Translation\Component\Localize\Time;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\Conversation;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Window\Stage;
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
        return new Service();
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

//        $File = tempnam( sys_get_temp_dir(), 'tryamltest' ).'.yaml';
//        Debugger::screenDump( $File );
//        /** @var SymfonyYaml $Setting */
//        $Setting = Document::getDocument( $File );


        $T = new Translate(
            new Translate\Group(__METHOD__,
                new Translate\Group('Identifier.1',
                    new Translate\Group('Identifier1',
                        new Translate\Group('Identifier.A')
                    )
                )
            ),
            (new Translate\Preset(
                '{% if(Anzahl < 1) %} Keine Äpfel :( {% else %} {% if(Anzahl > 1) %} {{ Anzahl }} Äpfel :D kosten {{ Kosten }} {% else %} {{ Anzahl }} Apfel :) {% endif %} {% endif %}',
                new Translate\Parameter(array(
                    'Anzahl' => 2,
                        'Kosten' => (new Localize( 40 ))->getCurrency()
                    )
//                ),
//                    'Anzahl'
                ),
                Translate\Preset::LOCALE_DE_DE
            ))
//                ->addPattern('!^0$!is', '{{ Anzahl }} Apfel')
//                ->addPattern('!^1$!is', '{{ Anzahl }} Apfel')
//                ->addPattern('!^[0-9]+$!is', '{{ Anzahl }} Äpfel')
        );

        $F = new TextField('',
            'Number',
            'Number'
        );

        $FT = new TextField('',
            $this->translateSimple( array( 'Textfeld', 'Platzhalter' ), 'Number' ),
            $this->translateSimple( array( 'Textfeld', 'Label' ), 'Number' )
        );

//        $Path = array_merge_recursive( $T->getDefinition(), $T2->getDefinition() );
//        $Setting->setContent( $Path );
//        $Setting->saveFile(new FileParameter($File), 10);
//        Debugger::screenDump(
//            $Path,
//            file_get_contents( $File )
//        );

        return (new Stage( $T ))->setContent( $F. $FT. 'Drücken Sie auf '.new Conversation().' :)'.' <br/> '. (new Localize( time() ))->getDateTime() );
    }
}
