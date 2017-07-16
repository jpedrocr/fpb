<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Traits\CrawlFPBTrait;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Club;

class Association extends Model
{
    use CrudTrait;
    use CrawlFPBTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'associations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = [
        'category_id', 'fpb_id', 'name', 'image', 'president', 'technical_director', 'cad_president', 'address',
        'telephone', 'fax_number', 'email', 'url'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $dates = ['created_at', 'updated_at'];
    protected $appends = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function competitions()
    {
        return $this->hasMany('App\Models\Competition');
    }
    public function clubs()
    {
        return $this->hasMany('App\Models\Club');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'fpb_id';
    }
    public static function updateOrCreateFromFPB($fpb_id, $update = true)
    {
        $association = Association::where('fpb_id', $fpb_id);
        if (($association->count()==0) or ($update)) {
            $crawler = self::crawler('http://www.fpb.pt/fpb2014/!site.go?s=1&show=ass&id='.$fpb_id);

            $content = $crawler->filterXPath('//div[@id="dConteudosx"]');

            $association_details = $content->filterXPath('//div/table[@class="TabelaHor01"]/tr/td');

            $original_address = explode("<br>", trim($association_details->eq(3)->html()));
            $address1 = trim($original_address[0]);
            $address2 = trim($original_address[1]);

            return Association::updateOrCreate(
                [
                    'fpb_id' => $fpb_id
                ],
                [
                    'category_id' =>
                        Category::firstOrCreate(['fpb_id' => 'ass'])->id,
                    'name' =>
                        trim($content->filterXPath('//div/div[@class="Assoc_FichaHeader_Nome"]/div')->text()),
                    'image' =>
                        $content->filterXPath('//div/div[@class="Assoc_FichaHeader_Foto"]/img')->attr('src'),
                    'president' =>
                        trim($association_details->eq(0)->text()),
                    'technical_director' =>
                        trim($association_details->eq(1)->text()),
                    'cad_president' =>
                        trim($association_details->eq(2)->text()),
                    'address' =>
                        implode("\n", $original_address),
                    'telephone' =>
                        trim($association_details->eq(4)->text()),
                    'fax_number' =>
                        trim($association_details->eq(5)->text()),
                    'email' =>
                        trim($association_details->eq(6)->filterXPath('//a')
                            ->evaluate('substring-after(@href, "mailto:")')[0]),
                    'url' =>
                        trim($association_details->eq(7)->filterXPath('//a')->attr('href')),
                ]
            );
        } else {
            return $association->first();
        }
    }
    public static function getAssociationsFromFPB()
    {
        Association::updateOrCreateFromFPB(50);
        return self::crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.109050;++BL(B1)+CO(B1)+'.
                'MYBASEDIV(dShowAssociacoes);+RCNT(10)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=ass&id=")]');
            },
            function ($crawler) {
                Association::updateOrCreateFromFPB(
                    $crawler->evaluate('substring-after(@href, "&id=")')[0]
                );
            }
        );
    }
    public function getCompetitionsFromFPB(Season $season)
    {
        $association = $this;
        return $this->crawlFPB(
            'http://www.fpb.pt/fpb2014/do?com=DS;1;.109030;++K_ID('.
                $this->fpb_id.')+K_ID_EPOCA('.
                $season->fpb_id.')+CO(PROVAS)+BL(PROVAS)+MYBASEDIV(dAssProvas);+RCNT(100)+RINI(1)&',
            function ($crawler) {
                return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=com&id=")]');
            },
            function ($crawler) use ($association) {
                Competition::updateOrCreateFromFPB(
                    $association->fpb_id,
                    $crawler->evaluate('substring-after(@href, "&id=")')[0]
                );
            }
        );
    }
    public function getClubsFromFPB($club_fpb_id = null)
    {
        $association = $this;
        if ($club_fpb_id!=null) {
            return $this->crawlFPB(
                'http://www.fpb.pt/fpb2014/do?com=DS;1;.109012;++K_ID('
                .$this->fpb_id.')+CO(CLUBES)+BL(CLUBES)+MYBASEDIV(dAssoc_Home_Clubes);+RCNT(1000)+RINI(1)&',
                function ($crawler) {
                    return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]');
                },
                function ($crawler) use ($association, $club_fpb_id) {
                    $fpb_id = $crawler->evaluate('substring-after(@href, "&id=")')[0];
                    if ($club_fpb_id==$fpb_id) {
                        Club::updateOrCreateFromFPB(
                            $fpb_id
                        );
                    }
                }
            );
        } else {
            return $this->crawlFPB(
                'http://www.fpb.pt/fpb2014/do?com=DS;1;.109012;++K_ID('
                .$this->fpb_id.')+CO(CLUBES)+BL(CLUBES)+MYBASEDIV(dAssoc_Home_Clubes);+RCNT(1000)+RINI(1)&',
                function ($crawler) {
                    return $crawler->filterXPath('//a[contains(@href, "!site.go?s=1&show=clu&id=")]');
                },
                function ($crawler) use ($association) {
                    Club::updateOrCreateFromFPB(
                        $crawler->evaluate('substring-after(@href, "&id=")')[0]
                    );
                }
            );
        }
    }
}
