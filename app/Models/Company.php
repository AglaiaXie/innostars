<?php namespace App\Models;

use App\Observers\CompanyObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 * @package App\Models
 * @property Industry $industry
 * @property string   $name
 * @property User     $user
 * @property boolean  $submit
 * @property boolean  $approval
 * @property string $company_name
 * @property string $website
 * @property string $contact_name
 * @property string $contact_title
 * @property string $contact_phone
 * @property string $contact_email
 * @property string $project_name
 * @property string $project_stage
 * @property string $patents
 * @property string $refer
 * @property string $cooperation
 * @property string $cooperation_alt
 * @property string $team_description
 * @property JoinedCompetition[] $joined_competitions
 * @property SubIndustry $sub_industry
 * @property integer $current_step
 * @property integer $industry_id
 */
class Company extends Model
{
    const COMPANY_TYPES = [
        'Public Company',
        'Corporate Enterprise/Group',
        'Limited Liability Company/Partnership',
        'University/College',
        'Academic Associations',
        'Independent Research Organization',
        'Government Department',
        'Non-profit',
        'Other',
    ];

    const COOPERATION_TYPES = [
        'Technical Transformation',
        'Technology License',
        'Cooperative R&D',
        'Mergers and Acquisition',
        'Equity Investment',
        'Manufacturing Technology; Equipment Transfer',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'size',
        'established',
        'website',
        'address',
        'address2',
        'city',
        'zip_code',
        'state',
        'country',
        'industry_id',
        'sub_industry_id',
        'contact_name',
        'contact_title',
        'contact_phone',
        'contact_email',
        'project_name',
        'project_description',
        'project_stage',
        'patents',
        'cooperation',
        'cooperation_alt',
        'team_description',
        'tech_requirement',
        'tech_advantage',
        'tech_risk',
        'business_value',
        'refer',
        'been_to_china',
        'approval',
        'submit',
        'current_step',
    ];

    protected $appends = ['preliminary_competition'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function sub_industry()
    {
        return $this->belongsTo(SubIndustry::class);
    }

    public function logo()
    {
        return $this->belongsTo(File::class, 'logo_id');
    }

    public function contact_photo()
    {
        return $this->belongsTo(File::class, 'contact_photo_id');
    }

    public function joined_competitions()
    {
        return $this->hasMany(JoinedCompetition::class);
    }

    public function getPreliminaryCompetitionAttribute()
    {
        $joinedPreliminaryCompetition = $this->joined_competitions()
            ->whereHas('competition', function (Builder $builder) {
                $builder->isPreliminary();
            })->first();

        return array_get($joinedPreliminaryCompetition, 'competition');
    }

    public function executive_summary()
    {
        return $this->belongsTo(File::class, 'executive_summary_id');
    }

    public function pitch_deck()
    {
        return $this->belongsTo(File::class, 'pitch_deck_id');
    }

    public function other_file_1()
    {
        return $this->belongsTo(File::class, 'other_file_1_id');
    }

    public function other_file_2()
    {
        return $this->belongsTo(File::class, 'other_file_2_id');
    }

    public function getWebsiteAttribute($website)
    {
        if  ( $ret = parse_url($website) ) {

            if ( !isset($ret["scheme"]) )
            {
                return "http://{$website}";
            }
        }

        return $website;
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(CompanyObserver::class);
    }
}
