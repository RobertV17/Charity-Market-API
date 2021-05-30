<?php


namespace App\Dto;


class Dto
{
    /**
     * @param array|null $data
     *
     * @return static
     */
    public static function populateByArray(array $data = null): self
    {
        $dto = new static();

        foreach ($dto->attributes() as $attribute) {
            $dto->$attribute = $data[$attribute] ?? null;
        }

        return $dto;
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        $class = new \ReflectionClass($this);
        $attributes = [];

        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes []= $property->getName();
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array) $this;
    }
}
