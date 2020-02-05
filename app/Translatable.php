<?php
/*
 * @author: Chris White
 * https://cwhite.me/translating-eloquent-fields-with-mysqls-native-json-type/
 */

namespace App;

trait Translatable
{
    /**
     * Returns a model attribute.
     *
     * @param $key
     * @return string
     */
    public function getAttribute($key)
    {
        if (isset($this->translatable) && in_array($key, $this->translatable)) {
            return $this->getTranslatedAttribute($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Returns a translatable model attribute based on the application's locale settings.
     *
     * @param $key
     * @return string
     */
    protected function getTranslatedAttribute($key)
    {
        $values = $this->getAttributeValue($key);
        $primaryLocale = config('app.locale');
        $fallbackLocale = config('app.fallback_locale');

        if (!$values) {
            return null;
        }

        if (!isset($values[$primaryLocale])) {
            // We don't have a primary locale value, so return the fallback locale.
            // Failing that, return an empty string.
            return $values[$fallbackLocale] ?: '';
        }

        return $values[$primaryLocale];
    }

    /**
     * Determine whether the provided attribute should be casted as JSON when it is being set.
     * If it is a translatable field, it should be casted to JSON.
     *
     * @param $key
     * @return bool
     */
    protected function isJsonCastable($key)
    {
        if (isset($this->translatable) && in_array($key, $this->translatable)) {
            return true;
        }

        return parent::isJsonCastable($key);
    }

    public function toArray()
    {
        $array = parent::toArray();

        foreach ($array as $key => $attribute) {
            if (isset($this->translatable) && in_array($key, $this->translatable)) {
                $array[$key] = $this->getTranslatedAttribute($key);
            }
            else {
                $array[$key] = $attribute;
            }
        }
        return $array;
    }
}
