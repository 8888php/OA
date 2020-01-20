------------ t_research_cost  ---------------
------- 20200120 新增字段---------
ALTER TABLE t_research_cost ADD subsidy DECIMAL(10,2) COMMENT'人员和劳务补助费' AFTER other3; 
ALTER TABLE t_research_cost ADD servicefee DECIMAL(10,2) COMMENT'科研服务费' AFTER other3; 
ALTER TABLE t_research_cost ADD activitiesfee DECIMAL(10,2) COMMENT'科研活动费' AFTER other3;


