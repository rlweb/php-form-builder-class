<?php
namespace PFBC\View;

class SideBySide extends \PFBC\View {
	protected $class = "form-horizontal";

	public function render() {
		$this->_form->appendAttribute("class", $this->class);

		echo '<form role="form"', $this->_form->getAttributes(), '><fieldset>';
		$this->_form->getErrorView()->render();

		$elements = $this->_form->getElements();
		$elementSize = sizeof($elements);
		$elementCount = 0;
		for($e = 0; $e < $elementSize; ++$e) {
			$element = $elements[$e];
			$prevElement = $elements[$e-1];
			if (!$prevElement){
				$prevElement = new \PFBC\Element\HTML("");
			}

			if($element instanceof \PFBC\Element\Hidden || $element instanceof \PFBC\Element\HTML)
				$element->render();
            elseif($element instanceof \PFBC\Element\Button) {
                if($e == 0 || !$elements[($e - 1)] instanceof \PFBC\Element\Button)
					echo '<div class="form-actions">';
				else
					echo ' ';
				
				$element->render();

                if(($e + 1) == $elementSize || !$elements[($e + 1)] instanceof \PFBC\Element\Button)
                    echo '</div>';
            }
            else {
				$element->appendAttribute("class", "form-control");
				if (!$prevElement->getAttribute("shared"))
					echo '<div class="form-group">', $this->renderLabel($element), '<div class="col-md-6">';
				echo $element->render(), $this->renderDescriptions($element);
				if (!$element->getAttribute("shared"))
					echo '</div></div>';
				else
					echo '&nbsp;&nbsp;&nbsp;';
				++$elementCount;
			}
		}

		echo '</fieldset></form>';
    }

	protected function renderLabel(\PFBC\Element $element) {
        $label = $element->getLabel();
        if(!empty($label)) {
			echo '<label class="col-md-4 control-label" for="', $element->getAttribute("id"), '">';
			if($element->isRequired())
				echo '<span class="required">* </span>';
			echo $label, '</label>'; 
        }
    }
}
