<?php
namespace SPHERE\Application\Example\Download\Excel;

use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\System\Extension\Repository\Debugger;

class Excel implements IModuleInterface
{

    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __CLASS__, 'frontendExcel', 'Excel', new Blackboard(), 'Excel');

    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    public function frontendExcel($Transfer = null) {

        $FilePointer = new FilePointer( 'xls' );

        $Document = Document::getDocument($FilePointer->getFileLocation());

        $Document->setValue( $Document->getCell(1,1), 'Value' );

        $Document->saveFile();

        print FileSystem::getDownload( $FilePointer->getFileLocation() );
    }
}