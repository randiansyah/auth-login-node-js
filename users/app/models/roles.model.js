module.exports = function (ConnRoles, Sequelize) {
	const Roles = ConnRoles.define('roles', {	
	  	id: {
			allowNull: false,
			type: Sequelize.INTEGER,
			autoIncrement: true,
			primaryKey: true
   	 	},
		name: {
			allowNull: false,
			type: Sequelize.STRING
		},
		description: {
			allowNull: false,
			type: Sequelize.STRING
		},
		is_deleted: {
			allowNull: false,
			type: Sequelize.INTEGER
		}
	}, {
		freezeTableName: true 
	});
	
	return Roles;
}