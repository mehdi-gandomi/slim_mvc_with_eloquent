<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Artisan extends Command
{
    protected $commandName = 'make:controller';
    protected $commandDescription = "Creates a controller";

    protected $ControllerName = "name";
    protected $ControllerNameDescription = "The name of the Controller that you want to create?";

    protected $CreateModelOption = "model"; // should be specified like "app:greet John --cap"
    protected $CreateModelOptionDescription = 'If set, it will Create a model as well';

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->ControllerName,
                InputArgument::OPTIONAL,
                $this->ControllerNameDescription
            )
            ->addOption(
                $this->CreateModelOption,
                null,
                InputOption::VALUE_NONE,
                $this->CreateModelOptionDescription
            );
    }
    protected function create_model($name)
    {
        $ModelFullPath=dirname(__DIR__)."/App/Models/".$name.".php";
        $tableName=strtolower($name."s");
        $ModelClass="<?php \nnamespace App\Models;\nuse Illuminate\Database\Eloquent\Model;\n\nclass Post extends Model{\n\t".'protected $table = \''.$tableName."';\n\tprotected".'$fillable'. "= [];\n}";
        if(file_exists($ModelFullPath)){
            return false;
        }
        
        $res=file_put_contents($ModelFullPath,$ModelClass, FILE_APPEND);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ControllerName = $input->getArgument($this->ControllerName);
        $ControllerClassTopSection ="<?php \nuse \Core\Controller;";
        if ($input->getOption($this->CreateModelOption)) {
            $ControllerClassTopSection.="\nuse App\\Models\\".$ControllerName.';';
            $this->create_model($ControllerName);
        }
        $ControllerClassBottomSection='class '.$ControllerName."sController extends Controller \n{ \n \t".'public function index($req,$res,$args)'."\t{ \n \t\techo 'hello world!';  \n \t} \n}";

        $ControllersPath=dirname(__DIR__)."/App/Controllers/";
        $ControllerFileName=$ControllerName.'sController';
        $ControllerFullPath=$ControllersPath.$ControllerFileName.".php";
        $FileContents=$ControllerClassTopSection."\n".$ControllerClassBottomSection;
        
        if(file_exists($ControllerFullPath)){
            return $output->writeln("Controller Already Exists!");   
        }
        
        $res=file_put_contents($ControllerFullPath,$FileContents, FILE_APPEND);
        if($res){
            return $output->writeln("Controller Create Successfully!");   
        }else{
            return $output->writeln("An Error Occured While Creating the Controller!");   
        }
    }
}
