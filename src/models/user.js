'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class User extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
  // Một sinh viên có nhiều luận văn
      User.hasMany(models.Thesis, { as: 'StudentTheses', foreignKey: 'studentId' });

  // Một giảng viên có nhiều luận văn
      User.hasMany(models.Thesis, { as: 'LecturerTheses', foreignKey: 'lecturerId' });

  // Một giảng viên có nhiều lần phê duyệt
      User.hasMany(models.Approval, { foreignKey: 'lecturerId' });
    }
  }
  User.init({
    fullName: DataTypes.STRING,
    email: DataTypes.STRING,
    password: DataTypes.STRING,
    role: {
  type: DataTypes.ENUM('student', 'lecturer', 'admin'),
  allowNull: false
}
  }, {
    sequelize,
    modelName: 'User',
  });
  return User;
};