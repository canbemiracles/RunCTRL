<?php


namespace ApiBundle\DataFixtures\Traits;


trait FillEntityTrait
{
    public function fillEntityFromArray(array $data, $entity, $new = true)
    {

        if($new) {
            $entity = new $entity;
        }

        foreach ($data as $property => $value) {
            if (is_array($value)) {
                $property = 'add' . ucwords($property);
                foreach ($value as $collectionItem) {
                    $entity->$property($collectionItem);
                }
            }else{
                $property = 'set' . ucwords($property);
                $entity->$property($value);
            }
        }

        return $entity;
    }
}
