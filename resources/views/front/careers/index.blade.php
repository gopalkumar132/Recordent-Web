@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.careers_page.title'))
@section('meta-description', config('seo_meta_tags.careers_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}careers" />
@endsection
@section('content')
<section class="about-info careers-section">
    <div class="container">
        <div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">
            <h2>Careers</h2>
        </div>
        <!-- <div id="accordion" class="myaccordion ask-questions">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <div class="position-relative">
                        <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Frontend Developer
                            <i class="fa-inverse"></i>
                        </button>
                         <div class="btn-apply">
                            <a href="{{route('careers.apply')}}">Apply</a>
                        </div>
                    </div>
                    
                   
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <p>Knowledge and Skills</p>
                        <ul>
                            <li>Extensive knowledge of OOCP</li>
                            <li>Extensive experience in UI development on PHP, Laravel based web applications</li>
                            <li>Experience in designing UI elements in PHP and integrating with Restful Services & Object models connecting DB</li>
                            <li>Experience in IDE Tools like Visual Studio code, Eclipse, SubLime, etc</li>
                            <li>Experience in Code Versioning tools like GitHub, SVN, etc.</li>
                            <li>Strong analytical and problem-solving skills</li>
                            <li>Ability to work both independently and as team player</li>
                            <li>Persuasive and strong relationship building skills</li>
                            <li>Continuously build knowledge about tools and technologies</li>
                            <li>Proactive and aptitude for working in matrix (hybrid) structure</li>
                        </ul>
                    </div>
                    <div class="card-body">    
                        <p>Qualification and Experience</p>
                        <ul>
                            <li>Graduate in Engineering/Computer Science/Information Systems</li>
                            <li>3-4 years of experience in web application development</li>
                        </ul>
                    </div>
                    <div class="card-body">    

                        <p>Job Responsibilities</p>
                        <ul>
                            <li>Design algorithms and flowcharts</li>
                            <li>Produce clean, efficient code based on specifications</li>
                            <li>Test and evaluate new programs; identify modifications in existing programs and develop new modifications</li>
                            <li>Integrate software components and third-party programs (if any)</li>
                            <li>Verify and deploy programs and systems</li>
                            <li>Troubleshoot, debug and upgrade existing software</li>
                            <li>Gather and evaluate user feedback</li>
                            <li>Recommend and execute improvements</li>
                            <li>Create technical documentation for reference and reporting</li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <div class="position-relative">
                        <button class="d-flex align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Backend Developer
                            <i class="fa fa-plus  fa-inverse"></i>
                        </button>    
                        <div class="btn-apply">
                            <a href="{{route('careers.apply')}}">Apply</a>
                        </div>
                    </div>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <p>Knowledge and Skills</p>
                         
                        <ul>
                            <li>Extensive knowledge of OOCP in JavaScript, Express JS</li>
                            <li>Extensive experience in frameworks like Angular JS, React Native, etc and Run Time like NodeJS</li>
                            <li>Experience in creating Restful services in Express JS using MYSQL DB</li>
                            <li>Experience in IDE tools like Visual Studio code, Eclipse, SubLime, etc</li>
                            <li>Experience with Code Versioning tools like GitHub, SVN, etc.</li>
                            <li>Strong analytical and problem-solving skills</li>
                            <li>Ability to work both independently and as team player</li>
                            <li>Persuasive and strong relationship building skills</li>
                            <li>Continuously build knowledge about tools and technologies</li>
                            <li>Proactive and aptitude for working in matrix (hybrid) structure</li>
                        </ul>
                    </div>
                    <div class="card-body">    

                        <p>Qualification and Experience</p>
                        <ul>
                            <li>Graduate in Engineering/Computer Science/Information Systems</li>
                            <li>3-4 years of experience in Development of Restful Services (JSON, XML, etc.)</li>
                        </ul>
                    </div>
                    <div class="card-body">    

                        <p>Job Responsibilities</p>
                        <ul>
                            <li>Design algorithms and flowcharts</li>
                            <li>Produce clean, efficient code based on specifications</li>
                            <li>Test and evaluate new programs; identify modifications in existing programs and develop new modifications</li>
                            <li>Integrate software components and third-party programs (if any)</li>
                            <li>Verify and deploy programs and systems</li>
                            <li>Troubleshoot, debug and upgrade existing software</li>
                            <li>Gather and evaluate user feedback</li>
                            <li>Recommend and execute improvements</li>
                            <li>Create technical documentation for reference and reporting</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> -->
        
        <iframe src="https://zfrmz.com/eGyM6YfhCBVAm6zluKBX" title="Zoho" width="100%" height="600"></iframe>
    </div>
</section>
<script>
    $(document).ready(function(){

        $("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
            $(e.target)
            .prev()
            .find("i:last-child")
            .toggleClass("fa-minus fa-plus");

        });
    });        
</script>                

@endsection