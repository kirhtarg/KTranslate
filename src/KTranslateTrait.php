<?php

namespace Vendor\kirhtarg\KTranslate;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

trait KTranslateTrait
{
    /**
     * Переводит указанное поле модели.
     *
     * @param string $field
     * @return mixed
     * @throws \Exception
     */
    public function KTranslate(string $field): string
    {
        $currentLocale = App::getLocale();
        $defaultLocale = Config::get('app.locale');

        if (!property_exists($this, $field)) {
            throw new \Exception(__('ktranslate::messages.welcome') . get_class($this));
        }

        // Если текущая локаль — локаль по умолчанию, возвращаем значение из модели
        if ($currentLocale === $defaultLocale) {
            return $this->$field;
        }

        // Проверка на локализацию в связанной таблице
        $translation = $this->localization()
            ->where('language', $currentLocale)
            ->where('field', $field)
            ->value('value');

        // Возвращаем перевод, если он существует, иначе значение по умолчанию
        return $translation ?? $this->$field;
    }

    /**
     * Определяет морфную связь для локализаций.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function localization()
    {
        return $this->morphMany(\App\Models\Localization::class, 'localizable');
    }
}
