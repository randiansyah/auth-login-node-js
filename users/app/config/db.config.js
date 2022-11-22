const env_users = require('./env_users.js');
const Sequelize = require('sequelize');

// Connection Users
const ConnUsers = new Sequelize(env_users.database, env_users.username, env_users.password, {
  host: env_users.host,
  dialect: env_users.dialect,
  operatorsAliases: false,
  define: {
    timestamps: false
  },
  pool: {
    max: env_users.max,
    min: env_users.pool.min,
    acquire: env_users.pool.acquire,
    idle: env_users.pool.idle
  }
});

const db = {};

db.Sequelize = Sequelize;
db.ConnUsers = ConnUsers;
 
db.Users = require('../models/users.model.js')(ConnUsers, Sequelize);
db.ApiKey = require('../models/apikey.model.js')(ConnUsers, Sequelize);
db.UsersRoles = require('../models/users.roles.model.js')(ConnUsers, Sequelize);
db.Roles = require('../models/roles.model.js')(ConnUsers, Sequelize);

db.UsersRoles.hasMany(db.Users, {foreignKey: 'user_id'})
db.Users.belongsTo(db.UsersRoles, {foreignKey: 'id'})

db.Roles.hasMany(db.UsersRoles, {foreignKey: 'id'})
db.UsersRoles.belongsTo(db.Roles, {foreignKey: 'role_id', targetKey: 'id'})

module.exports = db;