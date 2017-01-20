<?php

namespace EBMQ\Section;

use EBMQ\Questionnaire\Questionnaire;

class Application
{
    protected $sections = [];

    public function __construct(){}

    public function addSection($section)
    {
        return array_push($this->sections, $section);
    }

    public function getCurrentSection($alias = null)
    {
        foreach ($this->sections as $section) {
            // For the solicitud/actualizar route, get the $section where the field exists
            if ($alias != null) {
                foreach ($section->getFields() as $field) {
                    $field->isUpdating = true;
                    if ($alias == $field->getFieldAttr('alias')) {
                        $section->isUpdating = true;
                        return $section;
                    }
                }
            }
            if (!$section->isComplete()) {
                return $section;
            }
        }

        return null;
    }

    public function getSections(): array
    {
        return array_filter($this->sections, function($section){
            return $section->isVisible();
        });
    }

    public function isComplete(): bool
    {
        $sections = $this->getSections();
        foreach ($sections as $section) {
            return $section->isComplete();
        }

        return true;
    }
}