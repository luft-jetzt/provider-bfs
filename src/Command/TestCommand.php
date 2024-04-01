<?php

namespace App\Command;

use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\Color\RGB;
use Imagine\Image\Point;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'TestCommand',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $imagePath = 'https://uvi.bfs.de/Tagesgrafiken/EEr_Lueneburg_today.png';

        $arrContextOptions= [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        //file_put_contents('tmp.png', file_get_contents($imagePath, false, stream_context_create($arrContextOptions)));

        $imagine = new Imagine();

        $image = $imagine->open('tmp.png');

        $currentPoint = $this->detectCurrentPoint($image);

        dd($currentPoint);

        return Command::SUCCESS;
    }

    protected function detectCurrentPoint(ImageInterface $image): Point
    {
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        for ($x = 575; $x >= 0; --$x) {
            $point = new Point($x,  $height - 49);
            $color = $image->getColorAt($point);

            if ($color->getRed() !== $color->getGreen() || $color->getRed() !== $color->getBlue() || $color->getGreen() !== $color->getBlue()) {
                break;
            }
        }

        for ($y = $height - 49; $y > 0; --$y) {
            $point = new Point($x, $y);
            $color = $image->getColorAt($point);

            $image->draw()->dot($point, $image->palette()->color('f00'));
            $image->save('tmp2.png');

            if ($color->getRed() == $color->getGreen() && $color->getRed() == $color->getBlue()) {
                ++$y;

                break;
            }
        }

        return new Point($x, $y);
    }
}
