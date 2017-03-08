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
    use AppTrait;

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

        $File = tempnam( sys_get_temp_dir(), 'tryamltest' ).'.yaml';
        Debugger::screenDump( $File );
        /** @var SymfonyYaml $Setting */
        $Setting = Document::getDocument( $File );


        $T = new Translate(
            new Translate\Group(__METHOD__,
                new Translate\Group('Identifier.1',
                    new Translate\Group('Identifier1',
                        new Translate\Group('Identifier.A')
                    )
                )
            ),
            (new Translate\Preset(
                'Foo {{ P1 }} {{ P2 }} => {{P3}}  in Deutsch :) {{ Number }} {{P4}} {{P5}}',
                new Translate\Parameter(array(
                    'P1' => $File,
                    'P2' => time()+100,
                    'P3' =>(new Localize( time() ))->getDateTime(),
                    'P4' =>(new Localize( time() ))->getTime(),
                    'P5' =>(new Localize( time() ))->getDate(),
                    'Number' => (new Localize( 123456789.12345 ))->getCurrency()
                ),
                    'P3'
                ),
                Translate\Preset::LOCALE_DE_DE
            ))
                ->addPattern('!^[0-9]+$!is', '{{P2|date}} Nuff {{ P1 }}')
        );

        $T2 = new Translate(
            new Translate\Group(__CLASS__,
                new Translate\Group('Identifier.1',
                    new Translate\Group('Identifier2')
                )
            ),
            (new Translate\Preset(
                'Foo {{ P1 }} {{ P2|date }} in English? ;( {{P3}}',
                new Translate\Parameter(array(
                    'P1' => $File,
                    'TimeStamp' => time()+100,
                    'LocalTime' => date( 'd.m.Y H:i', time()+1000 )
                ),
                    'TimeStamp'
                ),
                Translate\Preset::LOCALE_EN_US
            ))
                ->addPattern('!^[0-9]+$!is', '{{LocalTime|date("d.m.Y")}} Deutsch :D Bar {{ P1 }}')
        );

        $Path = array_merge_recursive( $T->getDefinition(), $T2->getDefinition() );

        $Setting->setContent( $Path );
        $Setting->saveFile(new FileParameter($File), 10);

        Debugger::screenDump(
            $Path,
            file_get_contents( $File )

        );



        return new Stage(
            $T, $T2
//            'Normal',
//            new Localization(new Group('Stage', new Group('Headline', new Singular('Normal'))), 'Seite 1')

        /*
            new Localization(new Group('Stage',
                new Plural('Small', new Pattern(array('!^0$!', '!^[0-9]{1}$!', '!^[0-9]{2,}$!'))))
            )
        ,             new Localization(new Group('Stage', new Group('Headline', new Singular('Small'))))
        */
        );
    }
}
