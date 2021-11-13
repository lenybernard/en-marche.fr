<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;

abstract class AbstractTranslatableEntity
{
    protected function getFieldTranslations(string $field): array
    {
        if (!$this->getTranslations()->containsKey('fr')) {
            return [];
        }

        $french = $english = $this->translate('fr');

        if ($this->getTranslations()->containsKey('en')) {
            $english = $this->translate('en');
        }

        $getter = sprintf('get%s', ucfirst($field));

        return [
            'fr' => $french->$getter(),
            'en' => $english->$getter(),
        ];
    }

    abstract public function translate(?string $locale = null, bool $fallbackToDefault = true): TranslationInterface;

    /** @return Collection */
    abstract public function getTranslations();

    abstract public function addTranslation(TranslationInterface $translation): void;

    abstract public function removeTranslation(TranslationInterface $translation): void;

    abstract public function mergeNewTranslations();
}
