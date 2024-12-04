<?php

namespace Vendor\KTranslate;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

trait KTranslateTrait
{
    /**
     * Автоматическое подключение модели Localization.
     */
    public static function bootKTranslateTrait()
    {
        static::registerLocalizationModel();
    }

    /**
     * Переводит указанное поле модели.
     *
     * @param string $field
     * @return mixed
     * @throws \Exception
     */
    public function KTranslate(string $field)
    {
        $currentLocale = App::getLocale();
        $defaultLocale = Config::get('app.locale');

        if (!property_exists($this, $field)) {
            throw new \Exception("Поле '{$field}' не существует в модели " . get_class($this));
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function localization()
    {
        return $this->morphMany($this->getLocalizationModel(), 'localizable');
    }

    /**
     * Возвращает класс модели Localization.
     *
     * @return string
     */
    protected function getLocalizationModel(): string
    {
        return config('ktranslate.localization_model', \App\Models\Localization::class);
    }

    /**
     * Проверяет существование модели Localization, создает её, если отсутствует.
     */
    protected static function registerLocalizationModel(): void
    {
        $modelPath = app_path('Models/Localization.php');

        if (!file_exists($modelPath)) {
            // Создаем базовый файл модели Localization
            file_put_contents($modelPath, self::getLocalizationModelStub());
        }
    }

    /**
     * Возвращает содержимое шаблона модели Localization.
     *
     * @return string
     */
    protected static function getLocalizationModelStub(): string
    {
        return <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Localization extends Model
{
    protected \$fillable = ['localizable_type', 'localizable_id', 'language', 'field', 'value'];

    public function localizable(): MorphTo
    {
        return \$this->morphTo();
    }
}
PHP;
    }
}
