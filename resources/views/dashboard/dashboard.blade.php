@extends('layouts.app')
@section('content')
<div class="content-wrapper">
   <div class="form-border">

       <div class="dashboard-container">
           
           <div class="dashboard-row">
               <div class="dashboard-box">
                   <div class="box-header">
                       <div>
                           <div class="label">Today</div>
                           <div class="value-group">
                               <div class="value">$5.43k</div>
                               <div class="change positive">+0.5%</div>
                           </div>
                           <div class="label">Revenue</div>
                       </div>
                       <div>
                           <div class="value">123</div>
                           <div class="label">Orders</div>
                       </div>
                   </div>
                   <div class="graph-area"></div>
               </div>

               <div class="dashboard-box">
                   <div class="label">Facebook Ads (this month)</div>
                   <div class="metrics-row">
                       <div>
                           <div class="label">Spend</div>
                           <div class="value">$2.60k</div>
                           <div class="sub-label">vs last month</div>
                       </div>
                       <div>
                           <div class="label">Revenue</div>
                           <div class="value">$4.90k</div>
                           <div class="change negative">-6% vs last month</div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Middle Row -->
           <div class="dashboard-row">
               <div class="dashboard-box">
                   <div class="box-header">
                       <div>
                           <div class="value">$43.8k</div>
                           <div class="label">Total</div>
                       </div>
                       <div class="avg-value">
                           <div class="circle">$40.0</div>
                           <div class="label">Avg. order value</div>
                       </div>
                   </div>
                   <div class="graph-area"></div>
               </div>

               <div class="dashboard-box">
                   <div class="completions-header">
                       <div class="value">150</div>
                       <div class="change positive">+12 vs last month</div>
                   </div>
                   <div class="cac-row">
                       <span>CAC</span>
                       <span class="value">$17.32</span>
                   </div>
                   <div class="cancellation-section">
                       <div class="label">Cancellation reason</div>
                       <div class="reason-list">
                           <div class="reason-row"><span>Customer</span><span>40</span></div>
                           <div class="reason-row"><span>Declined</span><span>15</span></div>
                           <div class="reason-row"><span>Fraud</span><span>8</span></div>
                           <div class="reason-row"><span>Inventory</span><span>6</span></div>
                       </div>
                   </div>
               </div>
           </div>

           <div class="row">
            <div class="col-lg-3">
                <div class="dashboard-box" style = 'height:600px'>
                   <div class="box-header">
                       <div class="label">Additional Metrics 2</div>
                       <div class="value">$18.9k</div>
                   </div>
                   <div class="graph-area"></div>
               </div>
            </div>
            <div class="col-lg-3">
                <div class="dashboard-box" style = 'height:600px'>
                   <div class="box-header">
                       <div class="label">Additional Metrics 2</div>
                       <div class="value">$18.9k</div>
                   </div>
                   <div class="graph-area"></div>
               </div>
            </div>
         </div>         
           
       </div>
       
   </div>
</div>

<style>
.dashboard-container {
   padding: 20px;
   background: #f8fafc;
}

.dashboard-row {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 20px;
   margin-bottom: 20px;
}

.dashboard-box {
   background: white;
   border-radius: 8px;
   padding: 16px;
   box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.box-header {
   display: flex;
   justify-content: space-between;
   align-items: flex-start;
   margin-bottom: 20px;
}

.value-group {
   display: flex;
   align-items: baseline;
   gap: 8px;
}

.metrics-row {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 20px;
}

.label {
   color: #64748b;
   font-size: 13px;
   margin-bottom: 4px;
}

.value {
   font-size: 24px;
   font-weight: 600;
   color: #1e293b;
}

.change {
   font-size: 13px;
}

.change.positive {
   color: #10b981;
}

.change.negative {
   color: #ef4444;
}

.graph-area {
   height: 120px;
   margin-top: 16px;
   background: #f8fafc;
   border-radius: 4px;
}

.circle {
   width: 60px;
   height: 60px;
   background: #ecfdf5;
   border-radius: 50%;
   display: flex;
   align-items: center;
   justify-content: center;
   color: #059669;
   font-weight: 600;
}

.cac-row {
   display: flex;
   justify-content: space-between;
   align-items: center;
   margin: 20px 0;
}

.reason-row {
   display: flex;
   justify-content: space-between;
   margin-top: 8px;
}

.sub-label {
   color: #64748b;
   font-size: 13px;
}
</style>
@endsection
