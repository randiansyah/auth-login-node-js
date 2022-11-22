
// Connection to Database 
const db = require('../config/db.config.js');
const Bank = db.Bank;
const Users = db.Users;
const UsersRoles = db.UsersRoles;
const Roles = db.Roles;

//  Declaration Variable
const validator = require("email-validator");
const bcrypt = require('bcrypt');
const salt = bcrypt.genSaltSync(10);
const arrX = ["1", "2","3","4"];
const random = Math.floor(Math.random() * arrX.length);


exports.retrieveAll = (req, res) => {      

    Users.findAll({ 
        attributes: ['id', 'first_name', 'phone', 'email', 'created_on', 'is_deleted'],
        where: { 
            is_deleted: {
                $ne: null
            } 
        },
        include: [
            {
                model: UsersRoles,
                attributes: ["role_id"],
                include: [
                    {
                        model: Roles,
                        attributes: ["name"],
                    }
                ]
            }
        ]                 
    })
    .then(data => {
        res.status(200).json({
            message: true,
            data: data
        });
    })
    .catch(error => {
    console.log(error);
        res.status(500).json({
            message: "Error!",
            error: error
        });
    });

}

exports.create = (req, res) => {

    let users = {};

    try{                        

        users.first_name = req.body.first_name;
        users.phone = req.body.phone;
        users.email = req.body.email;
        users.gender = req.body.gender;
        users.password = bcrypt.hashSync(req.body.password, salt);            
        users.date_of_birth = req.body.date_of_birth;
        users.code_refferal = req.body.code_refferal;                
        users.photo = 'default/default-picture-'+arrX[random]+'.png';
        users.created_on = req.body.created_on;
        users.active = 1;
        users.is_deleted = 0;        
        
        if(users.first_name == null){
            res.status(400).json({
                message: "Nama harus diisi",
            });
        }
        else if(users.phone == null){
            res.status(400).json({
                message: "No ponsel harus diisi",
            });                
        } 
        else if(users.phone.length < 12){
            res.status(400).json({
                message: "No ponsel harus berisikan 12 digit",
            });                
        }                 
        else if(users.email == null ){
            res.status(400).json({
                message: "Email harus diisi",
            });              
        } 
        else if (!validator.validate(users.email)){
            res.status(400).json({
                message: "Email tidak valid",
            }); 
        }  
        else if(users.gender == null){
            res.status(400).json({
                message: "Jenis kelamin harus diisi",
            });                
        } 
        else if(users.password == null){
            res.status(400).json({
                message: "Password harus diisi",
            });                
        }
        else if(users.date_of_birth == null){
            res.status(400).json({
                message: "Tanggal lahir harus diisi",
            });                
        }else{             
            Users.create(users).then(result => {    
                res.status(200).json({
                    message: "Berhasil menambahkan data pengguna",
                    users: result,
                });
            });
        }
        
    }catch(error){
        res.status(500).json({
            message: "Gagal menambahkan data pengguna",
            error: error.message
        });
    }

}

exports.getById = (req, res) => {
        
    let id = req.params.id;

    Users.findOne({ 
        attributes: [
            'id', 
            'first_name', 
            'phone', 
            'email', 
            'gender', 
            'photo', 
            'created_on', 
            'is_deleted'
        ],
        where: { 
            id: id
        },
        include: [
            {
                model: UsersRoles,
                attributes: ["role_id"],
                include: [
                    {
                        model: Roles,
                        attributes: ["name"],
                    }
                ]
            }
        ]                 
    })            
    .then(users => {
        res.status(200).json({
            message: "true",
            users: users
        });
    })
    .catch(error => {
        console.log(error);
        res.status(500).json({
            message: "Error!",
            error: error
        });
    });
            
}

exports.updateById = async (req, res) => {

    try{

        let id = req.params.id;
        let users = Users.findOne({ 
            attributes: [
                'id', 
                'first_name', 
                'phone', 
                'email', 
                'gender', 
                'photo', 
                'created_on', 
                'is_deleted'
            ],
            where: { 
                id: id
            },
            include: [
                {
                    model: UsersRoles,
                    attributes: ["role_id"],
                    include: [
                    {
                        model: Roles,
                        attributes: ["name"],
                    }
                    ]
                }
            ]                 
        }) 
    
        if(!users){
            res.status(404).json({
                message: "Data tidak ditemukan",                
            });
        } else {    
            let updatedObject = {
                first_name : req.body.first_name,
                phone : req.body.phone,
                email : req.body.email,
                gender : req.body.gender,
                password : bcrypt.hashSync(req.body.password, salt),            
                date_of_birth : req.body.date_of_birth,
                code_refferal : req.body.code_refferal,                
                created_on : req.body.created_on,
            }
            
            let result = await Users.update(updatedObject, {returning: true, where: {id: id}});
            
            if(!result) {
                res.status(500).json({
                    message: "Error"
                });
            }

            res.status(200).json({
                message: "Berhasil mengedit data pengguna",
                users: updatedObject,
            });
        }
    } catch(error){
        res.status(500).json({
            message: "Error",
            error: error.message
        });
    }
             
}

exports.deleteById = async (req, res) => {

    try{
        let id = req.params.id;
        let users = await Users.findByPk(id);
        if(!users){
            res.status(404).json({
                message: "Data tidak ditemukan"
            });
        } else {
            await bank.destroy();
            res.status(200).json({
                message: "Data berhasil dihapus",
                users: users,
            });
        }
    } catch(error) {
        res.status(500).json({
            message: "Error",
            error: error.message,
        });
    }
             
}


// exports.filteringBy = (req, res) => {

//     let bank_name = req.query.bank_name;

//     Bank.findAll({ attributes: ['id', 'bank_name', 'bank_code', 'is_deleted'],
//                 where: {bank_name: bank_name} })
//     .then(results => {
//         res.status(200).json({
//             message: "true",
//             bank: results,
//         });
//     })
//     .catch(error => {
//         console.log(error);
//         res.status(500).json({
//             message: "Error!",
//             error: error
//         });
//     });
                       
// }
 
// exports.pagination = async (req, res) => {

//     try {

//         let page = parseInt(req.query.page);
//         let limit = parseInt(req.query.limit);
    
//         const offset = page ? page * limit : 0;
    
//         Bank.findAndCountAll({ limit: limit, offset:offset })
//         .then(data => {
//             const totalPages = Math.ceil(data.count / limit);
//             const response = {
//                 message: "true",
//                 data: {
//                     "totalItems": data.count,
//                     "totalPages": totalPages,
//                     "limit": limit,
//                     "currentPageNumber": page + 1,
//                     "currentPageSize": data.rows.length,
//                     "bank": data.rows
//                 }
//             };
//             res.send(response);
//         });  

//     } catch(error) {
//         res.status(500).send({
//         message: "Error -> Can NOT complete a paging request!",
//         error: error.message,
//         });
//     }               
// }

// exports.pagingfilteringsorting = (req, res) => {
//   try{
//     let page = parseInt(req.query.page);
//     let limit = parseInt(req.query.limit);
//     let age = parseInt(req.query.age);
  
//     const offset = page ? page * limit : 0;

//     console.log("offset = " + offset);
  
//     Customer.findAndCountAll({
//                                 attributes: ['id', 'firstname', 'lastname', 'age', 'address'],
//                                 where: {age: age}, 
//                                 order: [
//                                   ['firstname', 'ASC'],
//                                   ['lastname', 'DESC']
//                                 ],
//                                 limit: limit, 
//                                 offset:offset 
//                               })
//       .then(data => {
//         const totalPages = Math.ceil(data.count / limit);
//         const response = {
//           message: "Pagination Filtering Sorting request is completed! Query parameters: page = " + page + ", limit = " + limit + ", age = " + age,
//           data: {
//               "totalItems": data.count,
//               "totalPages": totalPages,
//               "limit": limit,
//               "age-filtering": age,
//               "currentPageNumber": page + 1,
//               "currentPageSize": data.rows.length,
//               "customers": data.rows
//           }
//         };
//         res.send(response);
//       });  
//   }catch(error) {
//     res.status(500).send({
//       message: "Error -> Can NOT complete a paging request!",
//       error: error.message,
//     });
//   }      
// }
