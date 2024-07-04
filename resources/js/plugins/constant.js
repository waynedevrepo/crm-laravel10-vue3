import { getUserRole } from "./auth"

const ALL = 'All'
const ROLE_ADMIN = 'Admin'
const ROLE_LEADER = 'Team Leader'
const ROLE_AGENT = 'Agent'

const STATUS_ACTIVE = 'active'
const STATUS_INACTIVE = 'inactive'

const rolesByAdmin = [
    ROLE_LEADER, 
    ROLE_AGENT
]

const rolesByTeamLeader = [
    ROLE_AGENT
]

const user_states = [
    STATUS_ACTIVE,
    STATUS_INACTIVE,
]

const filter_user_states = [
    ALL,
    STATUS_ACTIVE,
    STATUS_INACTIVE,
]

const allRoles = [
    ALL,
    ROLE_ADMIN,
    ROLE_LEADER, 
    ROLE_AGENT
]

const defaultUser = {
    username: "",
    name: "",
    email: "",
    password: "",
    role: ROLE_AGENT,
    status: STATUS_ACTIVE,
    team_leader: "",
    caller_id: 0,
    contact_number: "",
}

const defaultCategory = {
    id: -1,
    name: "",
    parent_id: -1
}

const defaultCampaign = {
    id: -1,
    name: "",
    company: "",
    category: "",
    sub_category: "",
    start_date: new Date(),
    end_date: new Date(),
    status: "active",
    today_sales: 0,
    total_sales: 0   
}

const tableOption = {
    page: 1,
    itemsPerPage: 5,
    sortBy: [''],
    sortDesc: [false],
}

const allStatus = [
    ALL,
    ...user_states
]

const rolesByManager = () => {
    const role = getUserRole()
    return role == ROLE_ADMIN ? rolesByAdmin : rolesByTeamLeader;
} 

const rolesByManagerForFilter = () => {
    const role = getUserRole()
    return role == ROLE_ADMIN ? allRoles : rolesByTeamLeader;
} 

const tableHeaders = {
    user: [
        {key: "username", title: "Username"},
        {key: "name", title: "Name"},
        {key: "team_leader", title: "Team Leader"},
        {key: "email", title: "Email"},
        {key: "role", title: "Role"},
        {key: "status", title: "Status"},
        {key: "created_at", title: "Created Date"},
        {key: "actions", title: "Action"}
    ],

    campaign: [
        { title: 'NAME', key: 'name' },
        { title: 'Company',  key: 'company' },
        { title: 'Category', key: 'category' },
        { title: 'Sub Category', key: 'sub_category' },
        { title: 'Start Date', key: 'start_date' },
        { title: 'End Date', key: 'end_date' },
        { title: 'Today Sales', key: 'today_sales' },
        { title: 'Total Sales', key: 'total_sales' },
        { title: 'STATUS', key: 'status' },
        { title: 'Action', key: 'actions' }
    ],
    campaignDetail: [
        {key: "id", title: "id"},
        {key: "campaignAgentRemark", title: "Campaign Agent Remark"},
        {key: "currentstatusdate", title: "Current Status Date"},
        {key: "applicanttypename", title: "Applicant Type Name"},
        {key: "applicantidentity", title: "Applicant Identity"},    
        {key: "applicantcompany", title: "Applicant Company"},
        {key: "applicantbusinessregistrationnumber", title: "Applicant Business Registration Number"},
        {key: "applicantname", title: "Applicant Name"},
        {key: "applicantgender", title: "Applicant Gender"},
        {key: "racename", title: "Race name"},
        {key: "applicantmobile", title: "Applicant Mobile"},
        {key: "applicantfax", title: "Applicant Fax"},
        {key: "applicantotherphone", title: "Applicant Other Phone"},
        {key: "applicantaddress1", title: "Applicant Address1"},
        {key: "applicantaddress2", title: "Applicant Address2"},
        {key: "applicantaddress3", title: "Applicant Address3"},
        {key: "applicantpostcode", title: "Applicant Postcode"},
        {key: "applicantcity", title: "Applicant City"},
        {key: "applicantstate", title: "Applicant State"},
        {key: "applicantemail", title: "Applicant Email"},
    
        {key: "installationaddress1", title: "Installation Address1"},
        {key: "installationaddress2", title: "Installation Address2"},
        {key: "installationaddress3", title: "Installation Address3"},
        {key: "installationpostcode", title: "Installation Postcode"},
        {key: "installationcity", title: "Installation City"},
        {key: "installationstate", title: "Installation State"},
        {key: "installationpropertytype", title: "Installation Property Type"},
        {key: "installationcontactperson", title: "Installation Contact Person"},
        {key: "installationcontactnumber", title: "installation Contact Number"},
        
        {key: "billingaddress1", title: "Billing Address1"},
        {key: "billingaddress2", title: "Billing Address2"},
        {key: "billingaddress3", title: "Billing Address3"},
        {key: "billingpostcode", title: "Billing Postcode"},
        {key: "billingcity", title: "Billing City"},
        {key: "billingstate", title: "Billing State"},
    
        {key: "productgroup", title: "Product Group"},
        {key: "productname", title: "Product Name"},
    ]
}

export {
    ALL,
    ROLE_ADMIN, ROLE_AGENT, ROLE_LEADER, allRoles,
    allStatus, defaultCampaign, defaultCategory, defaultUser, filter_user_states, rolesByAdmin, rolesByManager,
    rolesByManagerForFilter, rolesByTeamLeader, tableHeaders, tableOption, user_states
}

