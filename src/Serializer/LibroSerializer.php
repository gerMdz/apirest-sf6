<?php

namespace App\Serializer;

use App\Entity\Libro;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class LibroSerializer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private RequestStack $requestStack;
    private UrlHelper $urlHelper;

    /**
     * @param ObjectNormalizer $normalizer
     * @param UrlHelper $urlHelper
     */
    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($object, string|null $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        /** @var Libro $object */
        if(!empty($object->getImage())){
            $data['image'] =  $this->urlHelper->getAbsoluteUrl('storage/default/'. $object->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, string|null $format = null, array $context = []): bool
    {
        return $data instanceof Libro;
    }


}