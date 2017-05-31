<?php
namespace SPHERE\Application\Search;

use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Window\Stage;

class Frontend
{
    public function frontendSearch()
    {
        $Stage = new Stage('Suche');

        $Stage->setContent(
            new Layout( array(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(array(
                            $this->formSearch(),
                            new Container('')
                        ))
                    )
                ),
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            '...'
                        )
                    )
                , new Title('Ergebnisse')),
            ))
        );

        return $Stage;
    }

    public function formSearch()
    {
        return new Form(
            new FormGroup(array(
                new FormRow(
                    new FormColumn(
                        (new TextField('Search[Text]', '', 'Ich suche ..'))->setAutoFocus()
                    )
                ),
                new FormRow(
                    new FormColumn(array(
                        new CheckBox('Search[Option][1]','Preisliste', 1),
                        new CheckBox('Search[Option][2]','Einlagerung', 1),
                        new CheckBox('Search[Option][3]','RR-Kombination', 1),
                        new CheckBox('Search[Option][4]','...', 1),
                    ))
                ),
            ))
        , new Primary('Suchen'), '/Search');
    }
}