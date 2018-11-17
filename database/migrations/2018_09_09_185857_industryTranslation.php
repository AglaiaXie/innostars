<?php

use App\Models\Industry;
use App\Models\SubIndustry;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndustryTranslation extends Migration
{
    private $industryTranslation = [
        'Healthcare and Biotechnology' => '医疗健康和生物科技',
        'Advanced Manufacturing' => '先进制造',
        'Environmental Technology' => '可持续发展与环保',
        'Information and Communication Technology' => '物联网和信息通信',
        'Artificial Intelligence and Augmented/Virtual Reality' => '人工智能和虚拟现实',
        'Renewable Energy' => '新能源',
        'New Materials' => '新材料',
    ];

    private $subIndustrlTranslation = [
        'Next Generation Sequencing' => '次世代基因组技术',
        'Pharmaceuticals' => '制药',
        'Medical Device' => '医疗装置',
        'Digital Health' => '数字健康',
        'Genomics' => '基因组学',
        'Cancer Diagnosis &amp; Treatment' => '癌症诊断及治疗',
        'Precision Medicine' => '精准医疗',
        'Cell Therapy' => '细胞治疗',
        'Tumor Drug Development' => '肿瘤药物研发',
        'New Vaccines' => '新型疫苗',
        'PCR Technology and Diagnostic Reagents' => 'PCR技术及诊断试剂',
        'Biochip' => '生物芯片',
        'Automatic Transportation' => '自动化交通',
        '3D Printing' => '3D打印',
        'Next Generation Computer-Aided Design Software' => '次世代材料技术',
        'Computer-Aided Design Software' => '计算机辅助设计软件',
        'Robotics' => '机器人',
        'Autonomous Vehicle' => '无人驾驶汽车',
        'Additive Manufacturing' => '增材制造',
        'Energy Recycling' => '能源再利用',
        'Eco-protection' => '生态保护',
        'Water &amp; Wastewater Treatment' => '水处理及污水处理',
        'Recycling &amp; Waste' => '废物回收',
        'Air Emissions Control' => '废气排放控制',
        'Soil &amp; Water Pollution Treatment' => '土壤及水污染治理',
        'Smart Cities' => '智能城市',
        'Industrial IoT' => '工业物联网',
        'Connected Health' => '互联健康',
        'Smart Homes' => '智能家居',
        'Connected Cars' => '联网汽车',
        'Wearables' => '可穿戴设备',
        'Smart Utilities' => '智能工具',
        'Communications Equipment' => '通信设备',
        'Internet Software &amp; Services' => '互联网软件和服务',
        'Technology Hardware, Storage &amp; Peripherals' => '技术硬件、存储和外设',
        'Digital Assistants' => '数字助理',
        'Neurocomputers' => '神经计算机',
        'Embedded Systems' => '产品嵌入式系统',
        'Expert Systems' => '专家系统',
        'AR/VR Hardware' => '增强现实／虚拟现实硬件',
        'AR/VR Content' => '增强现实／虚拟现实内容',
        'AR/VR Data' => '增强现实／虚拟现实数据',
        'AR/VR Commerce' => '增强现实／虚拟现实商务',
        'Enterprise AR/VR' => '增强现实／虚拟现实的企业化应用',
        'Energy Storage Technology' => '能源存储技术',
        'Unconventional Oil &amp; Gas Exploration' => '非常规油气开采',
        'Energy Efficiency Improvement' => '能源效率改进',
        'Smart Grid &amp; Energy Storage' => '智能电网及储能',
        'Solar Energy' => '太阳能',
        'Biomass Energy' => '生物质能',
        'Hydro &amp; Wind Energy' => '水能及风能',
        'Metal/Metal Alloy' => '金属／金属合金',
        'Carbon Materials/Carbon Fiber' => '碳材料／碳纤维',
        'Composites' => '复合材料',
        'Membrane Materials' => '膜材料',
        'Polymer Materials' => '高分子材料',
        'Photoelectric Materials' => '光电材料',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('industries', function (Blueprint $table) {
            $table->string('name_cn')->after('name')->nullabel();
        });

        Schema::table('sub_industries', function (Blueprint $table) {
            $table->string('name_cn')->after('name')->nullabel();
        });

        Schema::table('investor_profiles', function (Blueprint $table) {
            $table->string('linkedin')->nullabel();
        });

        foreach ($this->industryTranslation as $name => $translation) {
            Industry::where('name', '=', $name)->update(['name_cn' => $translation]);
        }

        foreach ($this->subIndustrlTranslation as $name => $translation) {
            SubIndustry::where('name', '=', $name)->update(['name_cn' => $translation]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
