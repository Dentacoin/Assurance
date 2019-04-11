<?php

namespace App\Http\Controllers;

use App\Page;
use App\PagesHtmlSection;
use App\TemporallyContract;
use Illuminate\Support\Facades\DB;
use Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\MenuElement;
use App\Menu;
use Dompdf\Dompdf;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const currencies = ['USD', 'EUR', 'GBP', 'RUB', 'INR', 'CNY', 'JPY'];
    const DCN_REWARD = 20000;

    public function __construct() {
        if(!empty(Route::getCurrentRoute()) && !Request::isMethod('post'))    {
            View::share('mobile', $this->isMobile());
            View::share('meta_data', $this->getMetaData());
            View::share('sections', $this->getDbSections());
            View::share('gas_estimation', $this->getGasEstimationFromEthgasstation());
            View::share('countries', $this->getApiCountries());
            /*View::share('current_user_country_code', $this->getCurrentCountryCode());*/
        }
    }

    public static function instance() {
        return new Controller();
    }

    protected function getMetaData()    {
        return Page::where(array('slug' => Route::getCurrentRoute()->getName()))->get()->first();
    }

    public function getMenu($menu_slug) {
        return MenuElement::where(array('menu_id' => Menu::where(array('slug' => $menu_slug))->get()->first()->id))->get()->sortBy('order_id');
    }

    protected function getDbSections()    {
        $meta_data = $this->getMetaData();
        if(!empty($meta_data)) {
            return PagesHtmlSection::where(array('page_id' => $this->getMetaData()->id))->get()->all();
        }else {
            return null;
        }
    }

    protected function isMobile()   {
        return (new Agent())->isMobile();
    }

    protected function getSitemap() {
        $sitemap = App::make("sitemap");
        // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
        // by default cache is disabled
        //$sitemap->setCache('laravel.sitemap', 3600);

        // check if there is cached sitemap and build new only if is not
        //if(!$sitemap->isCached())  {
        // add item to the sitemap (url, date, priority, freq)

        $sitemap->add(URL::to('/'), '2019-03-20T20:10:00+02:00', '1.0', 'weekly');
        $sitemap->add(URL::to('/patient'), '2019-03-20T20:10:00+02:00', '1.0', 'weekly');
        $sitemap->add(URL::to('/wallet-instructions'), '2019-03-20T20:10:00+02:00', '0.8', 'weekly');
        $sitemap->add(URL::to('/support-guide'), '2019-03-20T20:10:00+02:00', '0.8', 'weekly');
        $sitemap->add(URL::to('/forgotten-password'), '2019-03-20T20:10:00+02:00', '0.3', 'monthly');

        /*//getting all pagination pages for testimonials
        for($i = 1, $length = (new UserExpressionsController())->getPagesCount(); $i <= $length; $i+=1) {
            $sitemap->add(URL::to('testimonials/page/'.$i), '2018-08-25T20:10:00+02:00', '0.7', 'daily');
        }

        //getting all pagination pages for press-center
        for($i = 1, $length = (new PressCenterController())->getPagesCount(); $i <= $length; $i+=1) {
            $sitemap->add(URL::to('press-center/page/'.$i), '2018-08-25T20:10:00+02:00', '0.7', 'daily');
        }

        //getting all pagination pages for press-center
        foreach((new \App\Http\Controllers\Admin\CareersController())->getAllJobOffers() as $career)    {
            $sitemap->add(URL::to('careers/'.$career->slug), '2018-10-10T20:10:00+02:00', '0.5', 'weekly');
        }*/

        // get all posts from db
        //$posts = DB::table('posts')->orderBy('created_at', 'desc')->get();
        //
        //// add every post to the sitemap
        //foreach ($posts as $post)
        //{
        //   $sitemap->add($post->slug, $post->modified, $post->priority, $post->freq);
        //}
        //}
        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml');
    }

    protected function transliterate($str) {
        return str_replace(['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','_'], ['a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya','-','-'], mb_strtolower($str));
    }

    public function minifyHtml($response)   {
        $buffer = $response->getContent();
        if(strpos($buffer,'<pre>') !== false) {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\r/"                      => '',
                "/>\n</"                    => '><',
                "/>\s+\n</"                 => '><',
                "/>\n\s+</"                 => '><',
            );
        }
        else {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\n([\S])/"                => '$1',
                "/\r/"                      => '',
                "/\n/"                      => '',
                "/\t/"                      => '',
                "/ +/"                      => ' ',
            );
        }
        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
        $response->setContent($buffer);
        ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!
        return $response;
    }

    public function minifyHtmlParts($html) {
        $replace = array(
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/"                  => '<?php ',
            "/\n([\S])/"                => '$1',
            "/\r/"                      => '',
            "/\n/"                      => '',
            "/\t/"                      => '',
            "/ +/"                      => ' ',
        );
        return preg_replace(array_keys($replace), array_values($replace), $html);
    }

    protected function refreshCaptcha() {
        return response()->json(['captcha' => captcha_img()]);
    }

    protected function getAllClinicsResponse() {
        $clinics = (new APIRequestsController())->getAllClinicsByName();
        if(!empty($clinics)) {
            return response()->json(['success' => $clinics]);
        } else {
            return response()->json(['error' => 'No clinics found at the moment. Try again later.']);
        }
    }

    protected function clearPostData($data) {
        foreach($data as &$value) {
            if(is_string($value)) {
                $value = trim(strip_tags($value));
            }
        }
        return $data;
    }

    protected function getGasEstimationFromEthgasstation()  {
        //API connection
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://ethgasstation.info/json/ethgasAPI.json',
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $resp = json_decode(curl_exec($curl));
        curl_close($curl);
        if(!empty($resp))   {
            return $resp->safeLow;
        } else {
            return false;
        }
    }

    protected function getApiCountries() {
        if((new UserController())->checkSession()) {
            return (new APIRequestsController())->getAllCountries();
        } else {
            return false;
        }
    }

    /*protected function getCurrentCountryCode() {
        if((new UserController())->checkSession()) {
            return mb_strtolower(trim(file_get_contents('http://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] .  '/country')));
        } else {
            return false;
        }
    }*/

    /*public function fillCountriesFromCsv() {
        $row = 1;
        if (($handle = fopen(ROOT . 'public' . DS . 'assets' . DS . 'countries-test.csv', 'r')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\n")) !== FALSE) {
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c+=1) {
                    $row_data = explode('|',$data[$c]);
                    $parameter = new CalculatorParameter();
                    $parameter->country = $row_data[3];
                    $parameter->code = $row_data[1];
                    $parameter->slug = $row_data[2];
                    $parameter->phone_code = $row_data[4];
                    $parameter->param_gd_cd_id = $row_data[5];
                    $parameter->param_gd_cd = $row_data[6];
                    $parameter->param_gd_id = $row_data[7];
                    $parameter->param_cd_id = $row_data[8];
                    $parameter->param_gd = $row_data[9];
                    $parameter->param_cd = $row_data[10];
                    $parameter->param_id = $row_data[11];
                    $parameter->save();
                }
            }
            var_dump('done');
            fclose($handle);
        }
    }*/

    protected function encrypt($raw_text, $algorithm, $key) {
        $length = openssl_cipher_iv_length($algorithm);
        $iv = openssl_random_pseudo_bytes($length);
        $encrypted = openssl_encrypt($raw_text, $algorithm, $key, OPENSSL_RAW_DATA, $iv);
        //here we append the $iv to the encrypted, because we will need it for the decryption
        $encrypted_with_iv = base64_encode($encrypted) . '|' . base64_encode($iv);
        return $encrypted_with_iv;
    }

    protected function decrypt($encrypted_text) {
        list($data, $iv) = explode('|', $encrypted_text);
        $iv = base64_decode($iv);
        $raw_text = openssl_decrypt($data, getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY'), 0, $iv);
        return $raw_text;
    }

    protected function base64ToPng($base64_string) {
        return base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64_string));
    }

    protected function getIpfsHashes() {
        return TemporallyContract::whereNotNull('contract_active_at')->get(['document_hash', 'contract_active_at']);
    }
}

