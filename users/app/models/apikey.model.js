module.exports = function (ConnApiKey, Sequelize) {
	const ApiKey = ConnApiKey.define('api_keys', {	
	  id: {
			allowNull: false,
			type: Sequelize.INTEGER,
			autoIncrement: true,
			primaryKey: true
   	 	},
		user_id: {
			allowNull: false,
			type: Sequelize.INTEGER
		},
		key: {
			allowNull: false,
			type: Sequelize.STRING
		},
		level: {
			allowNull: false,
			type: Sequelize.INTEGER
        },
		ignore_limits: {
			allowNull: false,
			type: Sequelize.INTEGER
        },
		is_private_key: {
			allowNull: false,
			type: Sequelize.INTEGER
        },
		ip_addresses: {
			allowNull: false,
			type: Sequelize.STRING
        },                        
        
	});
	
	return ApiKey;
}