<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    #[Assert\NotBlank()]
    #[
        Assert\Length(
            min: 2,
            max: 20,
            minMessage: 'Le nom doit avoir au minimum 2 caractères',
            maxMessage: 'Le nom ne doit pas excéder 20 caractères'
        )
    ]
    public string $name = '';

    #[Assert\NotBlank()]
    #[Assert\Email(message: 'Ceci n\'est pas une adresse email valide')]
    public string $email = '';

    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 1000)]
    public string $message = '';

    #[Assert\NotBlank()]
    public string $services = '';
}
