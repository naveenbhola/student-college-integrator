<?php $this->load->view('shikshaSchema/header'); ?>

<div style='margin-bottom: 20px;'>
<h1>Location Tables</h1>
<h3>Locations are stored in master tables which are mostly static and very infrequently updated. The locations stored are countries, states, cities, zones, localities and regions. A regions represents a global area consisting of countries e.g. South East Area is a region consisting of Malysia, Philipines etc. There is also a concept of virtual cities e.g. Delhi NCR is a virtual city consisting of Delhi, Noida, Gurgaon etc.</h3>
</div>
<div style="margin-bottom: 30px; box-shadow:0 0 5px #666; -moz-box-shadow: 0 0 5px #666; -webkit-box-shadow: 0 0 5px #666;">
    <a href='/public/location.png'>
        <img src='/public/location.png' width='840' />
    </a>
</div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'countryTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'stateTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'countryCityTable')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'virtualCityMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tZoneMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'localityCityMapping')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tregion')); ?></div>
<div class='tableBox'><?php $this->load->view('shikshaSchema/printTable',array('tableName' => 'tregionmapping')); ?></div>

<?php $this->load->view('shikshaSchema/footer'); ?>