module.exports = function (ConnApiKey, Sequelize) {
	const Users = ConnApiKey.define('users', {	
	    id: {
			allowNull: false,
			type: Sequelize.INTEGER,
			autoIncrement: true,
			primaryKey: true
		},
		password: {
			allowNull: false,
			type: Sequelize.STRING
		},
		active: {
			allowNull: false,
			type: Sequelize.INTEGER
		},
		photo: {
			allowNull: false,
			type: Sequelize.STRING
		},
		date_of_birth: {
			allowNull: false,
			type: Sequelize.STRING
		},
		code_refferal: {
			allowNull: false,
			type: Sequelize.STRING
		},
		first_name: {
			allowNull: false,
			type: Sequelize.STRING
		},
		phone: {
			allowNull: false,
			type: Sequelize.STRING
		},
		email: {
			allowNull: false,
			type: Sequelize.STRING
        },
		gender: {
			allowNull: false,
			type: Sequelize.STRING
        },
		created_on: {
			allowNull: false,
			type: Sequelize.STRING
        },
		is_deleted: {
			allowNull: false,
			type: Sequelize.INTEGER
        },                        
        
	});
	
	return Users;
}