<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ImageService
{
    private string $imagePath;

    private string $storageDir;
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private UserRepository $userRepository)
    {
        $this->imagePath = $this->parameterBag->get('imagePath');
        $this->storageDir = $this->parameterBag->get('storageDir');
    }

    public function removeImage(User $user): void
    {
        $imagePath = $this->imagePath . $user->getImage();
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $user->setImage('');
        $this->userRepository->save($user);
    }

    public function saveImage(User $user, $file): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        if(strtolower($file->guessExtension()) !== 'jpg' && strtolower($file->guessExtension()) !== 'png' && strtolower($file->guessExtension()) !== 'jpeg') {
            throw new \Exception('Niepoprawny format pliku. Dozwolone: JPG, JPEG, PNG.');
        }
        $file->move($this->storageDir, $fileName);
        $path = 'images/' . $fileName;

        $user->setImage($path);
        $this->userRepository->save($user);
        return $path;
    }
}